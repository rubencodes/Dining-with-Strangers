var tour = {
 id: "welcome_tour",
 steps: [
  {
    target: "options",
    placement: "bottom",
    title: "View Dates",
    xOffset: '-15px',
    content: "Using the View Dates tool, you can filter through all of the dates available. Search for Dining Dates or Romantic Dates, or perhaps take a look at the dates that you've created. You can even search by the day or locale!"
  },
  {
    target: "submitDate",
    placement: "bottom",
    title: "Create a Date",
    xOffset: '-15px',
    content: "The Create a Date tool allows you to make a date at any one of our affiliate local businesses for others to join. You might even qualify for discounts!"
  },
  {
    target: "submitEvent",
    placement: "bottom",
    title: "Create an Event",
    xOffset: '-15px',
    content: "The Create an Event tool allows you to make a custom event for others to join. This might be a potluck dinner, a video gaming session, or even a study party!"
  },
  {
    target: "messages",
    placement: "bottom",
    title: "See Your Messages",
    xOffset: '-15px',
    content: "The Messages page lets you view messages from and send messages to your current Dates. This way you can get to know the people you're eating with or meeting with, and you can arrange transportation details."
  },
  {
    target: "updateUser",
    placement: "bottom",
    title: "Your Profile",
    xOffset: '-15px',
    content: "Use the Update tool to make sure your information stays up to date. You only have to fill the information that's changed, everything else stays the same by default."
  },
  {
    target: "startTourBtn",
    placement: "bottom",
    title: "You're All Set",
    xOffset: '-15px',
    content: "Remember, you can access Dining with Strangers from any mobile browser as well. Visit the FAQ page for more specific questions, and don't forget to leave us feedback on the Comment Cards page. Thank you for Dining with Strangers!"
  }
 ],
 onEnd: function() { $('#startTourBtn').html("Again?"); }
},

init = function() {
  var startBtnId = 'startTourBtn',
      calloutId = 'startTourCallout',
      mgr = hopscotch.getCalloutManager(),
      state = hopscotch.getState();

  if (state && state.indexOf('welcome_tour:') === 0) {
    // Already started the tour at some point!
    hopscotch.startTour(tour);
  }
  else {
    // Looking at the page for the first(?) time.
    setTimeout(function() {
      mgr.createCallout({
        id: calloutId,
        target: startBtnId,
        placement: 'bottom',
        title: 'Take a Tour',
        content: 'Get started on your dining adventure by taking a tour through the Dining with Strangers website!',
        xOffset: '-115px',
        arrowOffset: 'center'
      });
    }, 100);
  }
  document.getElementById(startBtnId).addEventListener('click', function() {
    if (!hopscotch.isActive) {
      mgr.removeAllCallouts();
      hopscotch.startTour(tour);
    }
  }, false);
};

init();