<?php
/**
 * Class for sending an email.
 *
 * @category   Zend * @package    Zend_Mail
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Core_Mail extends Zend_Mail
{

    /**
     * Sets the HTML body for the message
     *     * @param  string    $html
     * @param  string    $charset
     * @param  string    $encoding
     * @return Zend_Mail Provides fluent interface
     */
    public function setBodyHtml($html, $charset = null, $encoding = Zend_Mime::ENCODING_QUOTEDPRINTABLE, $preload_images = true)
    {
        if ($preload_images)
        {
            $this->setType(Zend_Mime::MULTIPART_RELATED);
            $dom = new DOMDocument(null, $this->getCharset());
            @$dom->loadHTML($html);

            $images = $dom->getElementsByTagName('img');
            for ($i = 0; $i < $images->length; $i++)
            {
                $img = $images->item($i);
                $url = $img->getAttribute('src');
                $image_http = new Zend_Http_Client($url);
                $response = $image_http->request();

                if ($response->getStatus() == 200)
                {
                    $image_content = $response->getBody();

                    $pathinfo = pathinfo($url);
                    $mime_type = $response->getHeader('Content-Type');
                    $mime = new Zend_Mime_Part($image_content);
                    $mime->id = $url;
                    $mime->location = $url;
                    $mime->type = $mime_type;
                    $mime->disposition = Zend_Mime::DISPOSITION_INLINE;
                    $mime->encoding = Zend_Mime::ENCODING_BASE64;
                    $mime->filename = $pathinfo['basename'];

                    $this->addAttachment($mime);
                }
            }
        }

        return parent::setBodyHtml($html, $charset, $encoding);
    }
}
