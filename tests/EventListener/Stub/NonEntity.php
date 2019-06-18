<?php


namespace App\Tests\EventListener\Stub;


class NonEntity
{
    /**
     * @var string
     */
    protected $token;

    /**
     * @return string
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return NonEntity
     */
    public function setToken(string $token): NonEntity
    {
        $this->token = $token;
        return $this;
    }


}