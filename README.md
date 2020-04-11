# ETHOPay

## Accept $ETHO as a payment method on WooCommerce

Known Bugs:

* Addresses with a very long transaction history are very hard for ETHOPay to read due to the size of the files it has to download - we recommend starting your store with a fresh address and replacing it with a new address every 300-400 Transactions, the less TXs the quicker the plugin will work and the less likely it will hang.

* Missing TX's this is quite uncommon but it does happen from time to time. If an order has been "On Hold" for a decent amount of time (More than 15 Minutes), it's likely that the TX has been missed or the person hasn't paid - in the case of the TX being missed verify the payment using the Ether-1 explorer on https://explorer.ether1.org and manually update the order.

If you need any support please feel free to join us in discord: https://discord.gg/cJY583m

An installation video can be found at: https://www.youtube.com/watch?v=4t43qbzrEdg
