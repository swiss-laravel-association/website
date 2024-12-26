# NFC Cards

## Abstract

Every member receives a card with NFC capabilities. Those cards can be
scanned at the meetups and automatically register the cardholder for
that meetup.

## Architecture

> **Disclaimer**: I (pl) am no security specialist and this scenario is not
aimed for best security, but mere a play with often used mechanics to grant
a minimum of fraud-protection. 

The nfc connection and authentication is based upon two layers:
- Request authentication: The call can only be sent by a client app that was authorized with the code of the current meeting
- Member authentication: The NFC chips holds the UUID, the Name and the signature of both for the user

### Event auth

Every event has a window of availability in the api. Calls for this event can only be done during this period. 
To ensure the calls are only done from the meetup location (and not from home) and therefore ensure, people were
really there and did not only send a request to the api, every event has an additional code. That code
has to be entered in the client-app by a committee member to activate the app. The app will
then send the hashed code with every request. 

With this an authorized app, general member information about the meetup can be requested, eg. the amount of 
people already checked in.

### Member auth

The "credentials" of a member is saved on her/his card. It consists of the name the member goes by (and is 
written on the card), the uuid and the signature. This data is serialized in JSON string on a NFC record:

Signature: 



## Preparation of events

## Api
