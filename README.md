# APP-API
Designed to integrate Servatrix into sales applications

**Getting Started**

Firest declare the class with your appKey.

    $servatrixAA = new ServatrixAA('64114390ed7871e9920c42591fdeff43');
   
 **Connecting your first user**  

Call the buildConnectUrl function to get the URL you should redirect your user too.
The unique identifier ($userID in this example) will be passed on in the callback so you can assign the right token to the right user.
In most cases the unique identifier is the users id.

    $connectUrl = $servatrixAA->buildConnectUrl($userID);
    header("Location: $connectUrl);

**The Callback**

After the user succesfully connected your service to his Servatrix account we will send a Callback to the CallbackUrl you defined in the Servatrix App Panel containing the following data:

 1. identifier - the unique identifier you assigned with the buildConnectUrl call
 2. token - the user token you can use later on to get a list of the users products and create a serial for them
 3. secret - your application callback secret, you should always check if the provided secret is correct 


