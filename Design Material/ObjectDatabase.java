
class User{
	String username;	
	String email;
	String passward;
	Date lastUpdate;

	Vector<String> followee;
	Vector<String> follower;


	Vector<String> followStory;
	//Vector<String> createStory;
	Vector<String> comments;
	//Vector<String> writeNode;
	Vector<String> likeNode;
}

class Theme{
	String name;
	String introduction;
}

class Story{
	Stirng id;
	String title;
	String* theme;
	Date createDate;
	String introduction;
	String* creator;
	//Vector<String> nodeId;
	String<String> followUser;	
}

class Node{
	String id;
	String title;
	String content;
	Date createDate;
	
	String* writer;
	Vector<String> likeBy;
	String* story;


	String* linkTo;
	String* developFrom;
	

	Vector<String> quoteFrom;
	Vector<String> quoteBy;

	//String<String> comments;
}

class Comment{
	String commentID;
	String* userID;
	String* nodeID;
	String text;
	Date createDate;
}

