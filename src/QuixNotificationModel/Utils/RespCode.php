<?php

namespace AddonPaymentsSDK\QuixNotificationModel\Utils;


class RespCode
{
    private mixed $code;
    private ?string $message;
    private ?string $uuid;

    public function __construct(mixed $respCode)
    {
        $this->setCode(isset($respCode->code) ? $respCode->code : null);
        $this->setMessage(isset($respCode->message) ? $respCode->message : null);
        $this->setUUID(isset($respCode->uuid) ? $respCode->uuid : null);

    }

    private function setCode(mixed $code): void
    {
        $this->code = $code;
    }

    private function setMessage(?string $message): void
    {
        $this->message = $message;
    }

    private function setUUID(?string $uuid): void
    {
        $this->uuid = $uuid;
    }

    /**
     * Get the response code value.
     *
     * @return mixed The response code value.
     */
    public function getCode(): mixed
    {
        return $this->code;
    }

    /**
     * Get the response message associated with the code.
     *
     * @return string|null The response message or null if not found.
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * Get the UUID associated with the response code.
     *
     * @return string|null The UUID or null if not found.
     */
    public function getUUID(): ?string
    {
        return $this->uuid;
    }
}