<?php

namespace AddonPaymentsSDK;

class NotificationHandler
{
    private mixed $notificationCallback = null;



    /**
     * Sets the callback function to be used when a notification is received.
     *
     * @param mixed $callback The callback function to handle the notification.
     */
    public function setNotificationCallback(mixed $callback): void
    {
        $this->notificationCallback = $callback;
    }

    /**
     * Handles incoming notifications, typically sent via HTTP POST requests.
     * This method reads the raw POST data, typically XML or JSON, and passes it to the callback function.
     *
     * @throws \Exception If there's an error in fetching the input data.
     */
    public function handleNotification() : void
    {
        header("HTTP/1.1 200 OK");
        header("Content-Type: text/xml");


        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $raw = file_get_contents("php://input");
            if ($raw === false) {
                throw new \Exception("Failed to get input data.");
            }
            // Store the XML data in the database
            if (is_callable($this->notificationCallback)) {
                call_user_func($this->notificationCallback, $raw);  // Pass the current instance or any other data you want
            }
        }
    }
}
