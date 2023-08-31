## Installation

After loading the files of this repo to your server, there are just two directories to move to their final location:
```
mv addons/pressable/ <path-to-whmcs>/modules/addons/
mv servers/pressable/ <path-to-whmcs>/modules/servers/
```

## Activate & Configure the Addon

1. Navigate to the add-ons page (`<admin-url>/configaddonmods.php`)
(Wrench Icon > System Settings > Addon Modules)
2. Click “Activate” for the Pressable module
3. Select “Configure” for the Pressable module
4. Enter the Pressable API credentials ([see instructions](#pressable-api-credentials))

![Configure the Pressable Addon in WHMCS](https://github.com/pressable/mpcp-whmcs-integration/blob/ce2ed7e373ca8411bef4e7688279723afb67ae29/docs/ConfigureAddon.jpg)

## Configure your Products

1. Navigate to the Products/Services page (`<admin-url>/configproducts.php`)
(Wrench Icon > System Settings > Products/Services)
2. If you have not already, you will need to create a Group
   ![Creating a Product Group in WHMCS](https://github.com/pressable/mpcp-whmcs-integration/blob/ce2ed7e373ca8411bef4e7688279723afb67ae29/docs/CreateProductGroup.jpg)
3. If you have not already, from the Products/Services page create a new Product for reselling of the Pressable service.
   a. Select Product Type `Other`
   b. Be sure to give your product a name
   c. Select Module `Pressable`
4. After creating, you can edit the Product to configure the plan options, including the number of sites allowed (on the Module Settings tab of the product edit page)

## Managing your Client's Sites

From any client profile page in your WHMCS admin, you can select `Login as Owner` to manage their sites as needed.

As the client:
1. Click `View Details` on an active product
   ![Client's view of an active product](https://github.com/pressable/mpcp-whmcs-integration/blob/ce2ed7e373ca8411bef4e7688279723afb67ae29/docs/ActiveProduct.jpg)
2. Click `Manage Sites`
3. Add a site, or select one in the list if already added
   
   ![Add a site](https://github.com/pressable/mpcp-whmcs-integration/blob/ce2ed7e373ca8411bef4e7688279723afb67ae29/docs/CreateSite.jpg)

   ![Manage a site](https://github.com/pressable/mpcp-whmcs-integration/blob/ce2ed7e373ca8411bef4e7688279723afb67ae29/docs/ManageSite.jpg)

## Pressable API Credentials

1. In the MyPressable Control Panel navigate to the API section: https://my.pressable.com/api/applications
2. Create a new Application
3. Give it all permissions
4. Copy/Paste the `Client ID` and `Client Secret` into the [WHMCS addon configuration](#activate--configure-the-addon)
   
   ![Pressable API Permissions](https://github.com/pressable/mpcp-whmcs-integration/blob/ce2ed7e373ca8411bef4e7688279723afb67ae29/docs/ApiPermissions.jpg)
