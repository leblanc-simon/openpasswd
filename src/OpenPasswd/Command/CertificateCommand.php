<?php
/**
 * This file is part of the OpenPasswd package.
 *
 * (c) Simon Leblanc <contact@leblanc-simon.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenPasswd\Command;

use OpenPasswd\Core\Config;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CertificateCommand extends Command
{
    /**
     * @var InputInterface
     */
    private $input;

    /**
     * @var OutputInterface
     */
    private $output;

    private $name;
    private $email;
    private $password;
    private $hash;
    private $capass;
    private $filename;


    protected function configure()
    {
        $this
            ->setName('certificate')
            ->setDescription('Create a client certificate to allow a user to connect at OpenPasswd')
            ->addOption('name',     null, InputOption::VALUE_REQUIRED, 'The client\'s name')
            ->addOption('email',    null, InputOption::VALUE_REQUIRED, 'The client\'s email')
            ->addOption('password', null, InputOption::VALUE_OPTIONAL, 'The export password of the certificate', null)
            ->addOption('hash',     null, InputOption::VALUE_OPTIONAL, 'The openpasswd hash to store in certificate', null)
            ->addOption('capass',   null, InputOption::VALUE_OPTIONAL, 'The CA private key password', null)
            ->addOption('output',   null, InputOption::VALUE_OPTIONAL, 'The p12 filename (none if standart output', null)
        ;
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        $this->getOptions();

        list($private_key, $csr) = $this->getPrivateKeyAndCsr();
        $certificate = $this->getSigningCertificate($csr);
        $pkcs12 = $this->exportPkcs12($certificate, $private_key);

        if (null === $this->filename) {
            $this->output->writeln($pkcs12);
        } else {
            if (false === file_put_contents($this->filename, $pkcs12)) {
                $this->output->writeln('<error>Impossible to write PKCS#12 file</error>');
            } else {
                $this->output->writeln('<info>The PKCS#12 is exported into : '.$this->filename.'</info>');
            }
        }
    }


    private function getOptions()
    {
        $this->name = $this->input->getOption('name');
        $this->email = $this->input->getOption('email');

        $this->setFilename();

        $options = [
            'password'  => 'Set the certificate\'s export password',
            'hash'      => 'Set the OpenPasswd hash to store in the certificate',
            'capass'    => 'Set CA private key passphrase',
        ];
        $dialog = $this->getHelperSet()->get('dialog');

        $validator = function ($value) {
            if (empty($value) === true) {
                throw new \RuntimeException('This value can\'t be empty');
            }
            return $value;
        };

        foreach ($options as $option => $message) {
            if ($this->input->getOption($option) !== null) {
                $this->$option = $this->input->getOption($option);
                continue;
            }

            $this->$option = $dialog->askHiddenResponseAndValidate(
                $this->output,
                '<question>'.$message.' : </question>',
                $validator,
                false,
                null
            );
        }
    }


    /**
     * @return bool
     * @throws \RuntimeException    If the directory doesn't exist or isn't writable
     */
    private function setFilename()
    {
        if (null === $this->input->getOption('output')) {
            $this->filename = null;
            return false;
        }

        $filename = $this->input->getOption('output');
        if (0 === preg_match('#^(\./|\.\./|/)#', $filename)) {
            $filename = Config::get('openssl_storage').DIRECTORY_SEPARATOR.$filename;
        }
        $directory = pathinfo($filename, PATHINFO_DIRNAME);

        if (is_dir($directory) === false) {
            throw new \RuntimeException($directory.' must be directory');
        }

        if (is_writable($directory) === false) {
            throw new \RuntimeException($directory.' must be writable');
        }

        $this->filename = $filename;
        return true;
    }


    /**
     * @return array(private_key, csr)
     * @throws \RuntimeException
     */
    private function getPrivateKeyAndCsr()
    {
        list($openssl_options, $distinguished_name) = $this->getOpensslOptions();

        $private_key = openssl_pkey_new($openssl_options);
        if (false === $private_key) {
            throw new \RuntimeException('Impossible to generate private key');
        }

        $csr = openssl_csr_new($distinguished_name, $private_key, $openssl_options);

        return [$private_key, $csr];
    }


    /**
     * @return array(openssl_options, distinguished_name)
     */
    private function getOpensslOptions()
    {
        $openssl_options = array_merge(
            [
                'config' => '/etc/ssl/openssl.cnf',
                'digest_alg' => 'sha1',
                'private_key_bits' => 4096,
                'private_key_type' => OPENSSL_KEYTYPE_RSA,
                'x509_extensions' => 'v3_ca',
            ],
            Config::get('openssl_options', [])
        );

        $distinguished_name = array_merge(
            [
                'countryName' => 'FR',
                'stateOrProvinceName' => 'Nord',
                'localityName' => 'Lille',
                'organizationName' => 'Company',
                'organizationalUnitName' => 'IT',
            ],
            Config::get('openssl_dn', [])
        );

        $distinguished_name['commonName'] = $this->name;
        $distinguished_name['emailAddress'] = $this->email;
        $distinguished_name['openpasswd'] = $this->hash;

        return [$openssl_options, $distinguished_name];
    }


    /**
     * Generate and sign the client certificate
     *
     * @param   $csr
     * @return  resource
     * @throws  \RuntimeException
     */
    private function getSigningCertificate($csr)
    {
        $ca = Config::get('openssl_ca');
        $this->checkCa($ca);

        $certificate = openssl_csr_sign(
            $csr,
            'file://'.$ca['certificate'],
            ['file://'.$ca['private_key'], $this->capass],
            Config::get('openssl_duration', 365),
            null,
            $this->getSerial()
        );

        if (false === $certificate) {
            throw new \RuntimeException('Impossible to generate client certificate');
        }

        return $certificate;
    }


    /**
     * Check if the option of the CA is defined
     *
     * @param   array $ca
     * @throws  \InvalidArgumentException
     */
    private function checkCa(array $ca)
    {
        $options = [
            'private_key' => 'Private key',
            'certificate' => 'Certificate',
        ];

        foreach ($options as $option => $human) {
            if (isset($ca[$option]) === false) {
                throw new \InvalidArgumentException($human.' of the CA must be defined');
            }

            if (
                file_exists($ca[$option]) === false
                ||
                is_readable($ca[$option]) === false
            ) {
                throw new \InvalidArgumentException($human.' of the CA must exists and be readable');
            }
        }
    }


    /**
     * Return the next serial to use
     *
     * @return int  the next serial to use
     */
    private function getSerial()
    {
        $serial_filename = Config::get('openssl_serial');
        $serial = 0;
        if (true === file_exists($serial_filename)) {
            $serial = file_get_contents($serial_filename);
            if (true === is_numeric($serial)) {
                $serial = (int)$serial;
            }
        }

        $serial++;

        if (false === @file_put_contents($serial_filename, $serial)) {
            $this->output->writeln('<error>Impossible to write the new serial in '.$serial_filename.'</error>');
        }

        return $serial;
    }


    /**
     * Export the PKCS#12 certificat
     *
     * @param   resource|string     $certificate    The x509 certificate
     * @param   resource            $private_key    The private key of the certificate
     * @return  string                              The PKCS#12 certificate
     * @throws  \RuntimeException                   if the export failed
     */
    private function exportPkcs12($certificate, $private_key)
    {
        if (false === openssl_x509_export($certificate, $x509, true)) {
            throw new \RuntimeException('Impossible to export certificate in x509');
        }

        if (false === openssl_pkcs12_export($x509, $output, $private_key, $this->password)) {
            throw new \RuntimeException('Impossible to export PKCS#12');
        }

        return $output;
    }
}