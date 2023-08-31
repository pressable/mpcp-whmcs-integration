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

![Configure the Pressable Addon in WHMCS](https://github.com/pressable/mpcp-whmcs-integration/assets/11565712/0fec0191-d79d-46fc-9acd-f2b6d9bc60f4)

## Configure your Products

1. Navigate to the Products/Services page (`<admin-url>/configproducts.php`)
(Wrench Icon > System Settings > Products/Services)
2. If you have not already, you will need to create a Group
   ![Creating a Product Group in WHMCS](https://github.com/pressable/mpcp-whmcs-integration/assets/11565712/8615fc88-6375-4761-82bf-63b29e01f07b)
3. If you have not already, from the Products/Services page create a new Product for reselling of the Pressable service.
   a. Select Product Type `Other`
   b. Be sure to give your product a name
   c. Select Module `Pressable`
4. After creating, you can edit the Product to configure the plan options, including the number of sites allowed (on the Module Settings tab of the product edit page)

## Managing your Client's Sites

From any client profile page in your WHMCS admin, you can select `Login as Owner` to manage their sites as needed.

As the client:
1. Click `View Details` on an active product
   ![Client's view of an active product](https://github.com/pressable/mpcp-whmcs-integration/assets/11565712/c09f0428-b6a2-4764-874a-789e08c9e7aa)
2. Click `Manage Sites`
3. Add a site, or select one in the list if already added
   
   ![Add a site](https://github.com/pressable/mpcp-whmcs-integration/assets/11565712/636c070f-03c2-4a04-bbec-b5af2bbc8748)

   ![Manage a site](https://github.com/pressable/mpcp-whmcs-integration/assets/11565712/09cb76b4-3805-4a69-911e-3a3e47ee6259)

## Pressable API Credentials

1. In the MyPressable Control Panel navigate to the API section: https://my.pressable.com/api/applications
2. Create a new Application
3. Give it all permissions
4. Copy/Paste the `Client ID` and `Client Secret` into the [WHMCS addon configuration](#activate--configure-the-addon)
   
   ![Pressable API Permissions](https://github.com/pressable/mpcp-whmcs-integration/assets/11565712/f6501075-9c60-4088-bcd5-ddb7825798dd)
