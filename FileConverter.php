<?php
namespace AppBundle\Service;

use Psr\Log\LoggerInterface;

class FileConverter
{
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    private function exec($execString)
    {
        //check if convertion in progress
        exec("ps aux | grep '$execString' | grep -v 'grep' | awk '{print $2}'", $output, $execReturn);//get pid
        if (!empty($output)) {//last one is grep command
            exec("wait {$output[0]}");//wait for proccess to finish
        } else {
            //convert
            $result = exec($execString, $output, $execReturn);
            if ($execReturn > 0) {//some error must have happen
                $this->logger->alert("some error must have happen when converting file \r\n exec string: $execString \r\n" . $execReturn . $result . print_r($output, true));
            }
        }
    }

    public function convert($fromFile, $toFile, $fromFormat, $toFormat)
    {
        if ($fromFormat === 'word') {
            $transitionFormat = "odt";
            $transitionFilePath = explode('.', $fromFile);
            $transitionFilePath[count($transitionFilePath) - 1] = $transitionFormat;
            $transitionFilePath = implode('.', $transitionFilePath);
            if ($toFormat === $transitionFormat) {
                $this->exec("sudo doc2odt $fromFile");
            } else {
                //convert to odt => convert from odt to $format
                if (!file_exists($transitionFilePath)) {
                    $this->exec("sudo doc2odt $fromFile");
                }
                $this->exec("ebook-convert $transitionFilePath $toFile --enable-heuristics");
            }
        } else {
            $this->exec("ebook-convert $fromFile $toFile --enable-heuristics");
        }
    }
}