# General
The system is fairly simple. The Node.JS code mostly just serves web content. There are a few pages that are special.
Anything in the path `/cgi-bin` is a collection of special pages. Anything related to accesing stuff such as the database.

The backend database seperates data into a few groups to isolate interactions. The first is `users`, which is self explanitory.
The second is `accounts` which stores the data for bank accounts and their balances. The third is `ledger` which of course stores
the ledger of all transactions.

The reason for seperating the data like this is to allow for expandability, such as allowing users to have more than one account,
like saving accounts or otherwise.