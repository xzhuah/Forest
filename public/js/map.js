var links = [];
var nodes = {};
var width = $("#map").width(),
    height = 500;
var svg = d3.select("#map");
// var currentUserId = 0;

$.ajax({
    url: "https://forest-novel.herokuapp.com/nodebystoryid/" + storyId,
    crossOrigin: true,
    type: 'GET',
    accept: 'application/json'
}).done(function(nodeObjArr) {
    json = nodeObjArr;
    json.forEach(function(n) {
        if (!!n.developFrom) {
            links.push({
                source: n.developFrom.objectId,
                target: n.objectId,
                type: "licensing"
            });
        }
        if (!!n.linkTo) {
            links.push({
                source: n.objectId,
                target: n.linkTo.objectId,
                type: "resolved"
            });
        }
    });

    links.forEach(function(link) {
        link.source = nodes[link.source] || (nodes[link.source] = {
            objectId: link.source
        });
        link.target = nodes[link.target] || (nodes[link.target] = {
            objectId: link.target
        });
    });


    $.each(nodes, function(index, value) {
        var result = $.grep(json, function(e) {
            return e.objectId == value.objectId
        })[0];
        value.contentPreview = result.content.substr(0, 300) + "...";
        value.likeByNum = 0;
        if (!!result.likeBy)
            result.likeBy.length;
        value.title = result.title;
        $.ajax({
            // url: "https://forest-novel.herokuapp.com/userid/57109ca879bc44005f759c57",
            url: "https://forest-novel.herokuapp.com/userid/" + result.writer.objectId,
            crossOrigin: true,
            type: 'GET',
            accept: 'application/json'
        }).done(function(userObj) {
            value.author = userObj.username;
        });
    });

    var force = d3.layout.force()
        .nodes(d3.values(nodes))
        .links(links)
        .size([width, height])
        .linkDistance(100)
        .charge(-1200)
        .friction(0.1)
        .on("tick", tick)
        .start();

    svg.append("defs").selectAll("marker")
        .data(["suit", "licensing", "resolved"])
        .enter().append("marker")
        .attr("id", function(d) {
            return d;
        })
        .attr("viewBox", "0 -5 10 10")
        .attr("refX", 15)
        .attr("refY", -1.5)
        .attr("markerWidth", 6)
        .attr("markerHeight", 6)
        .attr("orient", "auto")
        .append("path")
        .attr("d", "M0,-5L10,0L0,5");

    var path = svg.append("g").selectAll("path")
        .data(force.links())
        .enter().append("path")
        .attr("class", function(d) {
            return "link " + d.type;
        })
        .attr("marker-end", function(d) {
            return "url(#" + d.type + ")";
        });

    var tip = d3.tip()
        .attr('class', 'd3-tip')
        .offset([-10, 0])
        .html(function(d) {
            return d.title;
        });

    svg.call(tip);

    c = d3.rgb("green");
    var circle = svg.append("g").selectAll("circle")
        .data(force.nodes())
        .enter().append("circle")
        .attr("r", function(d) {
            // according to likes number
            // var num = d.likeByNum;
            // if (num < 3)
            //     return 5;
            // else if (num < 10)
            //     return 5.5;
            // else if (num < 20)
            //     return 6;
            // else if (num < 40)
            //     return 6.5;
            // else if (num < 80)
            //     return 7;
            // else
            //     return 8;
            return 6;
        })
        .style("fill", function(d) {
            // according to stories
            var num = d.likeByNum;
            if (num < 3)
                return c.brighter(2).toString();
            else if (num < 10)
                return c.brighter(1).toString();
            else if (num < 20)
                return c.toString();
            else if (num < 40)
                return c.darker(1).toString();
            else if (num < 80)
                return c.darker(2).toString();
            else
                return c.darker(3).toString();
        })
        .on("click", function(d) {
            var $sideDiv = $("#node-info");
            $sideDiv.empty();
            $sideDiv.append("<div class='panel-body'><div class='list-group'>");
            var $sideTitle = $("<div class='list-group-item'><h1>" + d.title + "</h1></div>");
            var $sideAuthor = $("<div class='list-group-item'><h2>" + d.author + "</h2></div>");
            var $sideDisc = $("<div class='list-group-item'><p><i class='fa fa-leaf' aria-hidden='true'></i>  " + d.contentPreview + "</p></div>");
            var $sideLikes = $("<div class='list-group-item'><p><i class='fa fa-thumbs-o-up' aria-hidden='true'></i>  " + d.likeByNum + " likes</p></div>");
            var $sideRead = $('<button type="button" class="btn btn-primary btn-block">Read more</button>');
            $sideRead.click(function() {
                window.location = "read.php?nodeid=" + d.objectId + "&title=" + d.title;
            })
            $sideDiv.append($sideTitle).append($sideAuthor).append($sideDisc)
                .append($sideLikes).append($sideRead);
            $sideDiv.show();
        })
        .on('mouseover', tip.show)
        .on('mouseout', tip.hide)
        .call(force.drag);

    function tick() {
        path.attr("d", linkArc);
        circle.attr("transform", transform);
    }

    function linkArc(d) {
        var dx = d.target.x - d.source.x,
            dy = d.target.y - d.source.y,
            dr = Math.sqrt(dx * dx + dy * dy);
        return "M" + d.source.x + "," + d.source.y + "A" + dr + "," + dr + " 0 0,1 " + d.target.x + "," + d.target.y;
    }

    function transform(d) {
        return "translate(" + d.x + "," + d.y + ")";
    }
});