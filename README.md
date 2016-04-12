# Forest

It is deployed on Heroku: https://nic-forest.herokuapp.com/
 
## Background

* People’s desire to create their own novel
* Not everyone can afford to write a whole novel
* ...

**Traditional writing model**

Single thread (one beginning, one end), little writer...

**How might we**

* Enable many people to write a novel together.
* Enable a novel to have multiple threads.
* Enable a novel to develop without any limitation.
* ...
 
## System Analysis

Main idea: A novel can be viewed as a set of relative plots.

_A traditional novel can be shown as following:_


_How about create something like this?_






We get three different novel by combining plots in a tree structure. Novel constructors, based on the existing plots, can write different possible plots and connect them to appropriate position of the original story tree. Or, they can create a separate plots first, develop on that plot which will result in a new tree, and combine this tree with the original tree in an appropriate way. In short, novel constructors enjoy great freedom in this developing model. Both writers and readers can be inspired by other people’s ideas and create their own plots for the novel. This is a new way to do literary creation, and very creative way for reader to enjoy literary work.
 
## System requirement

* Reader: 
    * Can choose different paths to read a novel. 
    * Can evaluate and comments on each plot and the whole novel, 
    * Can choose to follow up a novel. Can choose to follow a writer. 
* Writer: 
    * Can create a tree node containing a plot, and link it to the tree. There are two kind of linkage 
        1. You can create a pointer from other nodes to your node. 
        2. You can create a pointer from your node to other nodes.   
        (This design serves for reader’s evaluation). Can quote other writers’ work. Can create theme.
* Novel creator: 
    * Can create a new novel with a root node inside.
 
## Information transfer

When a novel is updated, inform all followed up users. When a node is evaluated or quoted by other users, inform its writer. User can invite other users to help contribute on a novel.

## Database Design

