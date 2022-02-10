# CSE330

Tatsuro Murakami

Student ID: 491963

Username: mTatsuro

The URL of the portal: http://ec2-18-118-2-214.us-east-2.compute.amazonaws.com/~tatsuro/module3/news_portal.php

## How to log in
Once you click the log in button, you can log in by using one of (username, password) pairs: (wustl, 1853), (alice, 12345), (bob, password), (eve, pizza). We recommend using **(wustl, 1853)**, as the account has posted most stories. You can also choose to register a new user by filling the fields on the bottom of the log in page. 

If you are logged in and you posted a story with your username before, you see two sections on the portal page. At the top you see all the stories posted by anyone, and on the bottom you see all the stories you posted. You can only choose your stories to edit/delete. By clicking the "View comments" option, you are redirected to the comment portal, where you can post a comment for the specific story. Again, you can only edit/delete your comments. 

## Creative Portion
### Visit counter
Our website tracks the number of times each story is visited, store the counter in the database, and display it along with the content of the story. The news are also listed by the descending order of their populariy in the portal page. 

### Image with a story
We implemented the image function to our news website. Users can post their stories with an image associated with them, and the image is shown along with the story. For the testing purpose, see the story, **Meow (Hello World)**. You will see a specific image that is associated with the story. When a story does not have a specific iamge, the default image is displayed. 

Although each image is associated with a story, images are not stored in the database. Instead, the database only holds the file name of an image, and references to a specific folder. This design exposes the folder in which all the images are stored. However, as all the images uploaded are associated with stories which can be viewed by anyone, this design does not put important information at risk. On that note, we would like to know how we could hide the directory structure for images, as the trick we learned in module 2, readfile(), was not capable of displaying an image together with other contents of a story. 
