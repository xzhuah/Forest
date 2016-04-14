/*前端开发所看到的表名，前端开发不用考虑真实表名，属性名，以这个文件为准即可，后台会做封装*/
create table Client(
	username varchar(50) primary key,
	email varchar(100),
	passward varchar(50),
	
);
create table FollowUser(
	follower varchar(50) references Client,
	followee varchar(50) references Client,
	primary key(follower,followee)
);
create table Story(
	id int primary key,
	title Text,
	introduction Text,
	createDate datetime,
	creator varchar(50) references Client not null
);
create table FollowStory(
	username varchar(50),
	storyId int,
	primary key(username,storyId),
	foreign key(username) references Client,
	foreign key(storyId) references Story
);
create table Node(
	nodeID int,
	storyId int,
	title Text,
	plot Text,
	writor varchar(50),
	primary key (storyId,nodeID),
	foreign key(storyId) references Story,
	foreign key(writor) references Client

);
create table Theme(
	name varchar(50) primary key,
	introduction Text,
	creator varchar(50) references Client not null
);
create table ThemeRelation(
	parentTheme varchar(50) references Theme,
	childTheme varchar(50) references Theme,
	primary key(parentTheme,childTheme)
);
create table StoryTheme(
	storyId int references Story,
	themeName varchar(50) references Theme,
	primary key(storyId,ThemeName)

);
create table Likes(
	username varchar(50) references Client,
	nodeID int ,
	storyID int,
	foreign key(nodeID,storyID) references Node,
	primary key(username,nodeID,storyId)
);
create table Comment(
	username varchar(50) references Client,
	storyID int,
	nodeID int,
	createDate datetime,
	foreign key(nodeID,storyID) references Node,
	primary key(username,StoryID,nodeID,createDate)
);
create table Invitation(
	invitorName varchar(50),
	inviteeName varchar(50),
	storyId int,
	primary key(invitorName,inviteeName,storyId),
	foreign key(invitorName)references Client,
	foreign key(inviteeName)references Client,
	foreign key(storyId) references Story
);
create table LinkTo(
	parentStoryID int,
	parentID int,
	childStoryID int,
	childID int,
	foreign key(parentStoryID,parentID) references Node,
	foreign key(childStoryID,childID) references Node,
	primary key(parentStoryID,parentID,childStoryID,childID)

)
create table DevelopFrom(
	parentStoryID int,
	parentID int,
	childStoryID int,
	childID int,
	foreign key(parentStoryID,parentID) references Node,
	foreign key(childStoryID,childID) references Node,
	primary key(parentStoryID,parentID,childStoryID,childID)
)
create table QuoteFrom(
	parentStoryID int,
	parentID int,
	childStoryID int,
	childID int,
	foreign key(parentStoryID,parentID) references Node,
	foreign key(childStoryID,childID) references Node,
	primary key(parentStoryID,parentID,childStoryID,childID)
);