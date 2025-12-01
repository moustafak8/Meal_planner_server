# TODO List for Updating addOrUpdatemembers Function

- [x] Import the `household` model in `HouseMembersController.php`
- [x] Update the `addOrUpdatemembers` function to handle `invitecode` and `userid` from request
- [x] Add logic to find household by `invite_code`
- [x] Add check if household exists; return error if not
- [x] Add check if user is already a member; return appropriate response if yes
- [x] Create and save new `House_members` record if user is not a member
- [x] Return success or failure response
