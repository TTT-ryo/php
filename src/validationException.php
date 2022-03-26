<?php
//Exceptionを拡張→複数の文字列をエラー文として表示を行いたいが、単純なexceptionは複数の文字列を受け取ることができないため
class ValidationException extends Exception
{
    public function __construct(array $message = null, int $code = 0, Exception $previous = null)
    {
        parent::__construct(json_encode($message), $code, $previous);
    }

    public function getArrayMessage($assoc = true)
    {
        return json_decode($this->getMessage(), $assoc);
    }
}
?>