# simple-pay

## to do
- [x] Databases modeling
- [ ] Transfer endpoint
  - [x] `TransferControler` as a invoke controller
  - [x] `TransferPostRequest`
  - [x] Validate transfer
    - [x] Merchant only an send money
    - [x] Customer can send money to other customers or merchants
    - [x] Verify if user payer have balance to this transfer
    - [x] Verify is the user is authorized to make the transfer -> [Authorization Service](https://util.devi.tools/api/v2/authorize) (GET)
  - [x] Centralize business logic in services
  - [x] Centralize database queries in repositories
  - [ ] When receiving a transfer the user must be notified -> [Notification Service](https://util.devi.tools/api/v1/notify) (POST)
- [ ] Unit tests
- [x] Feature tests
- [ ] Docker
- [ ] Documentation (AI will do that)
- [x] Use Pint for code quality and style
- [x] Use PHP Stan for static analyze
