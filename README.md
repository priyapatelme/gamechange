# Display chosen products in a new customer frontend page accessed from the My Account section.
When you want to display product collection on frotend, based on the value of a custom product attribute, this extension is created for that very purpose.



# How to install this extension

Under the root of your folder, run the following commands-lines:

composer require gamechange/productlist
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento cache:flush
php bin/magento cache:clean



# How to see the results

1. Go to back end

2. By default the extension is enabled & the product limit value to display is 4

3. Go to Backend -> Store -> Configuration -> GameChange -> Product List

	you can configure below values as per your requirements:
	1. Module Enable	
	2. Set product collection limit

4. Go to frontend

5. Login to custom account

6. Go to My Account dashboard section

7. When the extension is enabled you can see the 'GameChange Products' tab on left bar

8. On the installation of this extension, you won't see any products in this section, because the custom products attribute which will be created on extension installation, value of this custom attribute 'handle_display' will be set to 'No' by default.

9. Change the value of custom attribute 'handle_display' to 'Yes' for the products which you want to show on this 'GameChange Products' section on front end.

10. You can adjust the number of products to display from back end configuration 'Set product collection limit'

11. This section is only visible by the logged in customers

12. This section and all other features of this extension will work only if the extension is enabled from the backend.