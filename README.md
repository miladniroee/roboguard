# Roboguard
___
A Telegram robot for easy management of Telegram and prevention of spam of people in your group.

## For use bot in groups

* Use the word alert to warn users. `alert`
  (Note: The user will be expelled from the group after 4 warnings by the robot.)


* Use the word delete alert to delete the alert.
`delete alert`

* Use the word ban to expel users.
`ban`

* Use the word info to get the group ID.
`info`

##Database Structure

Name | Type | Collation | Null | Default |Extra
------------ | ------------- | ------------- | ------------- | ------------- | -------------
id | int(100) | - | No | None | AUTO_INCREMENT
user | varchar(100) | latin1_swedish_ci | No | None | -
ekhtar | int(5) | - | No | None | -
