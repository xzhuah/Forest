
class Client{
	String username;	
	String email;
	String passward;
	Date lastUpdate;

	Vector<String> followee;
	Vector<String> follower;

	Vector<String> followStory;
	Vector<String> createStory;
	Vector<String> comments;
	Vector<String> writeNode;
	Vector<String> likeNode;
}

class Theme{
	String name;
	String introduction;
	Vector<String> storys;
}

class Story{
	Stirng id;
	String title;
	String* theme;
	Date createDate;
	String introduction;

	Vector<String> nodeId;
	String<String> followUser;
	String<String> comments;
	
}

class Node{
	String id;
	String title;
	String content;
	Date createDate;
	
	String* writer;
	Vector<String> likeBy;
	String* story;

	Vector<String> linkToParent;
	Vector<String> linkToChildren;

	Vector<String> developFromParent;
	Vector<String> developFromChildren;

	Vector<String> quoteFrom;
	Vector<String> quoteBy;
}

class Comment{
	String commentID;
	String* userID;
	String* nodeID;
	String text;
	Date createDate;
}

