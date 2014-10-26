# Requirement Specification

## PopHub

List popular users from Github depending on followers, repos or 
other interesting factors.

Follow users to get specific information from them without showing 
that they follow them on Github but follow them in this application.

# UC1 List popular users from Github
## Main scenario
1. Starts when a user sees all the users
2. The system present all the popular users according to followers on Github

## Alternate scenarios
* 2a. 
  1. User wants to sort users by language i.e. PHP
  2. The system rearrange the users according to the users choice

# UC2 Authenticate user
## Main scenario
1. Starts when a user want’s to authenticate
2. System redirects to GitHub
3. User provides username and password for GitHub
4. GitHub asks user to Authorize application
5. User accepts
6. GitHub redirects user back to the site
7. User is logged in

## Alternate scenarios
* 6a. User could not be authenticated
  1. Github presents an error message
  2. Step 3 in main scenario

# UC3 Logout user authenticated user
## Preconditions
1. A user is authenticated

## Main scenario
1. Starts when a user wants to logout
2. The system presents a logout option
3. User tells the system to logout him
4. System logs out the user
5. System redirects user to the home page

# UC4 Follow users from Github
## Preconditions
1. A user is authenticated

## Main scenario
1. Starts when a user sees all the users
2. System presents a follow user option
3. User tells the system to follow another user
4. System presents a success message
5. System show a unfollow user option instead

# UC5 Unfollow users from Github
## Preconditions
1. A user is authenticated

## Main scenario
1. Starts when a user sees all the users
2. System presents a unfollow user option
3. User tells the system to unfollow another user
4. System presents a success message
5. System show a follow user option instead


