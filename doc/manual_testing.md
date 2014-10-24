# Manual testing

## Testcase 1.1: Follow user

### Input
1. Navigate to /users/
2. Press Follow on user **torvals**

### Output
1. You now follow torvalds
2. Button now displays Unfollow

## Testcase 1.2: Can't follow user more than once

### Input
1. Navigate to /follow/torvalds

### Output
1. You already follow torvalds

## Testcase 2.1: Unfollow user

### Input
1. Navigate to /users/
2. Press Unfollow on user **torvals**

### Output
1. You no longer follow torvalds
2. Button now displays Follow

## Testcase 2.2: Can't unfollow user you do not follow

### Input
1. Navigate to /unfollow/jh222xk

### Output
1. You do not follow jh222xk so you cannot unfollow

## Testcase 3.1 Can walk through pages

### Input
1. Navigate to /users/
2. Press the "Last page" button

### Output
1. Users changes

## Testcase 3.2 Can't walk through a invalid page

### Input
1. Navigate to /users/?page=1000

### Output
1. Oops, it looks like /users/?page=1000 does not exist...

## Testcase 4.1 Can authenticate

### Input
1. Navigate to the home page
2. Press "Sign in through GitHub"
3. Fill in GitHub username and password
4. Press "Authorize application"

### Output
1. Redirects to /user/
2. Displays your username and some other information

## Testcase 5.1 Can logout

### Input
1. Navigate to the home page
2. Press "Logout"

### Output
1. Redirects to the home page

## Testcase 5.2 Is really logged out

### Input
1. Navigate to /user/

### Output
1. Redirects to the home page
