# magento-google-merchant-center-shopping-feed-atom
Made for Magento 1.9.0.1+ this script will make an atom xml document for Google's Merchant Center Shopping Feed

## Setup

Place the file into you magento install i.e. public_html

Update $mysitetitle with the website address

Update the $shipping* variables with data depending on your country(this does not have to be too accurate).

If necessary change date_default_timezone_set("Europe/London") to your time zone, if you encounter errors

## Feed Product Requirments

This script will only process products with the following attributes:

Enabled = true

Available = in stock

name

sku

UPC (upc) and or GTIN (gtin)

brand

Googlep Product Type (googleproducttype)

Short Description 


### Products Feed Specification
It is expected that you have met all requirements for Products Feed Specification You may need to add additional attributes if you are selling items such as clothing
>https://support.google.com/merchants/answer/188494?hl=en-GB 

### Google Product Type
It is recommended that you use the number references rather than the breadcrumb path for data entry as the & and > can cause problems.
>https://support.google.com/merchants/answer/160081?hl=en-GB


## FAQ

Q: Is this script free

A: Yes it has an MIT licence




Q: I need a atom xml feed for more than one country

A: You would be better off renaming the script and customising it for each country




Q: Is there an example output

A: Yes there is an example output supplied in "google-atom-feed-example.xml"




Q: My web browser is crashing/freezing when I run this script

A: Its your computer, either limit the number of loops the foreach makes when testing or use a different computer




Q: I am using firefox web browser and cannot see the out put XML

A: Use a different web browser such as Chrome



Q: How large will the output file be?

A: This will depend on the length of your product descriptions but in general 1.5 MB for each 1000 products




Q: Can I save this XML file?

A: Yes you can do a Ctr+S or use PHP to make a file using fopen




Q: I have a lot of products and the http/server is timing out what can I do

A: You can set your php.ini file to have a longer time out http://php.net/manual/en/function.set-time-limit.php.
However if you do not have access or have a PCI compliance scanning service this may cause problems so...

A: You can set up a cron job to run the script on the server, do not use echo $rss->asXML(); instead use the php fopen to make a new xml file on the server and point google to that.

