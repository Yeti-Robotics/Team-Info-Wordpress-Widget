#The YETI Team Info WordPress Widget

###How to use
1. Go to [thebluealliance.com](https://www.thebluealliance.com/) and find the event page for the event you wish to display your teams rankings for.
2. The URL should say: `www.thebluealliance.com/event/{the year}{the event key}`
3. Remember this event key, and head to: `http://yetirobotics.org/Programmers/widget.php?teamNumber={your team number}&eventKey={the event key}`
4. If you see a full-screen-width version of the widget, great! You can now include this in any page using an iframe.
5. To use in wordpress specifically, use a text widget and make sure the only text in it is this:
```<iframe height=305px src="http://yetirobotics.org/Programmers/widget.php?teamNumber={your team number}&eventKey={the event key}"></iframe>```

If your event has not yet started, and your team has no ranking info, not seeing anything is normal. We reccomend you display info on your last event until the FMS is updated and your team has info.
