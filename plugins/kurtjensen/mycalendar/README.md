## Installation
( Installation code : __kurtjensen.mycalendar__ ) 

Download the plugin to the plugins directory and logout and log in back into October backend. Go to the MyCalendar page in the backend and add your events.

# MyCalendar plugin

This plugin adds a simple calendar with events database feature to [OctoberCMS](http://octobercms.com).

* Easily add a calendar to your site and populate with your own event dates
* Store dates in a simple table through backend form

MyCalendar was built soley to be extended to do what you need.  You can use it as is or add fields with your own plugins.


Go to the MyCalendar page in the backend and add your events.

## Display Month calendar on page
- Drag "Month Component" to the page layout.

```
  {% component 'Month' %}
``` 
- Edit Page URL to provide "Next" and "Previous" link to allow users to scroll through months. ( /calendarpage/  with no month or year will default to current month and year)
```
    /calendarpage/:month?/:year?
```

## Display Month calendar on page and make it wider
- Drag "Month Component" to the page layout and edit as below.

```
  <style>
      table.mycal { width: 900px;}
  </style>

  {% component 'Month' %}
``` 


## Display Month calendar on page and insert events from DB
- Drag "Events Component" to add it to page. 
- Drag "Month Component" to the page layout and edit as below.

```
  {% component 'Events' %}
  {% component 'Month' events = MyEvents %}
```

The "Events Component" injects the MyEvents array into the page. It also has a modal pop-up for showing event details.


## Display Event list calendar on page and insert events from today to 60 days from now
- Drag "Events Component" to the page. 
- Drag "List Component" to the page layout and edit as below.
- In "Events Component" set property "Future Days" to 60

```
  <div style="width:100px">
  {% component 'Events' %}
  {% component 'EvList' events = MyEvents %}
  </div>
```

The "List Component" only shows up when there are events for the month indicated.
The "Events Component" injects the MyEvents array into the page.

## You have multiple optional properties for each component
- __Month__ (month) - The month you want to show. ( defaults to now )
- __Year__ (year) -The year you want to show. ( defaults to now )
- __Events__ (events) - Array of the events you want to show. 
- __Calendar Color__ (color) - What color do you want calendar to be? ( defaults to red )
- __Day Properties__ (dayprops) - Array of the properties you want to put on the day indicator.
- __Load Style Sheet__ (loadstyle) - Do you want to load the default stylesheet?

These properties can be set by clicking on component and changing them there or in the page layout as below:
    
  {% component 'Month' month = 2 events = MyEvents %}



## Passing you own "Events" and/or "Day Properties" to the calendar
If passing you own "Events" and/or "Day Properties" to the calendar using an array in the page, here is the formate used:

```
    /**
    * ======================================================
    * Sample of array format used to pass events to calendar
    * ======================================================
    **/
    $this->page['MyEvents'] = [
        2015 => [   // Year
            2 => [    // Month
                20 => [  // Day
                    [
                        'txt' => 'October CMS', 
                        'link' => 'http://octobercms.com/'
                    ], 
                    [
                        'txt' => '<p>Text to show on calendar</p>', 
                        // You can add properties to anchor tag if needed.
                        'link' => '#content-confirmation" data-toggle="modal',
                        // You can add a CSS class tag to the <li> tag for this event.
                        'class' => 'text-success',
                        // You can add an on-hover "title" property to the <li> tag for this event.
                        'title' => 'Just another test. This text shows on hover of event.' 
                    ],
                ],
                22 => [
                    [
                        'txt' => 'My really long and dumb event name',
                        'title' => 'More about my really long and dumb event named event.'
                    ],
                ],
            ],
        ]
    ];
    /**
    * ==============================================================
    * Sample of array format used to pass Day Properties to calendar
    * ==============================================================
    **/
    $this->page['MyDayProps'] = [
        2015 => [   // Year
            2 => [    // Month
                20 => [ // Day
                    // You can add a link to the day indicator in the calendar.
                    // ( This example was for a modal AJAX dialog box. )
                    'link' => '#content-confirmation" data-toggle="modal'
                ],
                22 => [
                    // You can add a CSS class to the day indicator in the calendar.
                    'class' => 'dis'
                ],
            ],
        ] 
    ];
```


## Thank You Early Users
Early users have discovered that several issues with the original design of this plugin.  I want to sincerely apologize 
for those missteps and flaws that became very apparent when December and January events did not show as expected on your calendars.  This is my third project involving calendars in PHP and each time I learn something new and often forget the many pitfalls of working with dates.  This plugin was no exception.

I very much appreciate your patience and assistance in finding and addressing issues that I should have found before first publishing.  I am committed to getting this right but there is still work to be done before I am completely satisfied with this product.  Please hang in there and continue to point out any issues you may discover.


## Did You Add Your Own Language File?
Please share it with the community by creating a pull request at https://github.com/firemankurt/MyCalendar or by contacting me directly using the contact form at https://octobercms.com/author/KurtJensen 


## Like this plugin?
If you like this plugin or if you use some of my plugins, you can help me by submiting a review in the market.

Please do not hesitate to find me in the IRC channel or contact me for assistance.
Sincerely 
Kurt Jensen
