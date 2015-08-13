# magento-google-merchant-center-shopping-feed-atom
Made for Magento 1.9.0.1+ this script will make an atom xml document for Google's Merchant Center Shopping Feed

## Setup

Place the file into you magento install i.e. public_html

Update $mysitetitle with the website address

Update the $shipping* variables with data depending on your country(this does not have to be too accurate).

If necessary change date_default_timezone_set("Europe/London") to your time zone, if you encounter errors

## Feed Product Requirments

This script will only process products with the following attributes:

Available = in stock

name

sku

UPC and or GTIN

brand

Googlep Product Type (googleproducttype)

Short Description 


### Products Feed Specification
It is expected that you have met all requirements for Products Feed Specification You may need to add additional attributes if you are selling items such as clothing
>https://support.google.com/merchants/answer/188494?hl=en-GB 

### Google Product Type
It is recommended that you use the number references rather than the breadcrumb path for data entry as the & and > can cause problems.
>https://support.google.com/merchants/answer/160081?hl=en-GB
