# SDK Integration Examples

This repository contains a set of example files designed to demonstrate various payment integration scenarios, including direct credit card processing, handling notifications, and using Quix services for specialized transactions.

## Structure Overview

you will find organized examples segmented by functionality, offering a clear pathway through different integration methods.

- `credentials.php`: Centralized storage for API credentials and common configuration settings applicable across various integration examples.

### Credit Cards

Dive into direct credit card transactions through detailed examples:

#### H2H (Host-to-Host)
- **H2H**
  - `h2h/h2h.php` & `h2h/index.html`: Basic direct credit card payment integration.
- **Authorization**
  - `authorization/authorization.php` & `authorization/index.html`: How to authorize a credit card payment.
- **Capture**
  - `capture/capture.php` & `capture/index.html`: Steps to capture a previously authorized payment.
- **Refund**
  - `refund/refund.php` & `refund/index.html`: Processing refunds to a credit card.
- **Void**
  - `void/void.php` & `void/index.html`: Cancelling transactions before settlement.  
- **Recurring**
  - `recurring/recurring.php` & `recurring/index.html`: Setting up initial recurring payment.
- **Recurring Subsequent**
  - `recurringSubsequent/recurringSubsequent.php` & `recurringSubsequent/index.html`: Managing subsequent transactions in a recurring setup.


#### Hosted
- **Hosted**
  - `hosted/hosted.php` & `hosted/index.html`: Implementing a hosted payment page.
- **Recurring**
  - `hosted/recurring.php` & `hosted/index.html`: Recurring payments via a hosted page.

#### JS
- `JS/auth.php`, `JS/charge.php`, `JS/index.html`: JavaScript and server-side endpoints for authentication and charging credit cards.


### Quix

Exploring Quix services for specialized transactions:

#### Hosted
- **Accommodation**, **Flights**, **Items**, **Service**
  - Server-side and client-side examples (`*.php` & `index.html`) for processing transactions through a hosted solution.

#### JS
- **Accommodation**, **Flights**, **Items**, **Service**
  - `auth.php`, `charge.php`, `index.html`: Integrating Quix services using JavaScript and server-side processing for specific service types.

### Notification

Handling notifications with precision:

- `notificationJson.php`, `notificationQuix.php`, `notificationWebhook.php`, `notificationXML.php`: Server-side endpoints for processing various types of notifications.
- **notification_samples**
  - `notification.json`, `notification.xml`, `quix_notification.json`: Sample payloads for different notification types.  

## Getting Started

To begin utilizing these examples, first ensure you have configured the necessary API credentials in `credentials.php`. Each example is crafted to function standalone, allowing for exploration and testing of different integration scenarios based on individual requirements.
