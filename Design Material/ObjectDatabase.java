class User{
	String username;
	String email;
	String password;
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
	String* creator;
	Vector<String> nodeId;
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

	Vector<String> linkToParent;
	Vector<String> linkToChildren;

	Vector<String> developFromParent;
	Vector<String> developFromChildren;

	Vector<String> quoteFrom;
	Vector<String> quoteBy;

	String<String> comments;
}

class Comment{
	String commentID;
	String* userID;
	String* nodeID;
	String text;
	Date createDate;
}
