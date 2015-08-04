<?php
    namespace Redpic\Antigate;

    class Captcha
    {
        private $image;
        private $key;

        public function getImage()
        {
            return $this->image;
        }

        public function getKey()
        {
            return $this->key;
        }

        public function setImageByBody($body)
        {
            $this->image = base64_encode($body);
            return $this;
        }

        public function setImageByPath($path)
        {
            return $this->setImageByBody(file_get_contents($path));
        }

        public function setImageByUrl($url)
        {
            return $this->setImageByPath($url);
        }

        public function setKey($key)
        {
            $this->key = $key;
            return $this;
        }
    }