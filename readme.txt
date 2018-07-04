to do the following:
1 Capture the Caller ID information for incoming calls
2 Add the Caller ID to a specific address book group where you already have Call Handling Rules that determine the calls routing based upon who is in that group
3 Avoid delays with obtaining the Caller ID info and adding it to the address book group so as to make sure that the logic to route based on that address book group will be in effect the next time they call in


Example:
1.  Person who calls in, hits an existing customer extension.  Lets call this extension 1234.
2.  We find a way to pull call logs of the CALLER that landed on extension 1234
3.  We capture that CALLER ID, then this caller ID is now added to another extension's Address Book.   (extension 6543)
4.  Once the CALLER ID has been added to this address book, Sub-Group (Contact Group), It will then follow the call handing rules set within the 6543 extension for that Contact Group. 



API Account ID: 691163
API Access Token: FhsDh235ILVOv9ezJcekPBQUION7bNR4ZrVggkon7Z6E1cYK
9062 - 1854207
9063 - 1854208


1.  Use the Create Listener API to wait for the "call.log" event.  With this API, your developer is able to assign the URL where his server code is located.  When a call is completed and call log is ready, the call log ID will be forwarded to the URL for his server code to process.

	Create Listener API:    https://apidocs.phone.com/docs/create-account-listener
	Account Event info:     https://apidocs.phone.com/docs/account-events

2.  With the call log ID from step 1) above, use the Get Call Log API to read call log details.  The Get Call Log API provides caller_id and extension information.

	Get Call Log API:    https://apidocs.phone.com/docs/get-account-call-log

3.  Parse for the the caller_id and extension information from step 2) above, use the List Group API to check if group exists; Use the Create Group API to create a new one if needed:

	List Groups API:      https://apidocs.phone.com/docs/list-account-extension-contact-groups
	Create Group API:  https://apidocs.phone.com/docs/create-account-extension-contact-group

4.  Use the List Contacts API to check if contact exists; Use the Create Contact API to add contact to the group if needed.

	List Contacts API:      https://apidocs.phone.com/docs/list-account-extension-contacts
	Create Contact API:  https://apidocs.phone.com/docs/create-account-extension-contact

