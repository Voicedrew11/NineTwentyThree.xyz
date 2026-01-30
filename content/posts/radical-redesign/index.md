+++
date = '2024-04-03T02:09:14-05:00'
draft = false
title = 'Radical Redesign'
summary = 'That time I replaced my very complicated website with a simple one'
+++

# Radical Redesign

Not long ago, I redesigned my entire website. I did away with my classic [Y2K](https://aesthetics.fandom.com/wiki/Y2K) aestetic in favor of a more modern frosted glass look in conjunction with a [holy grail layout](https://en.wikipedia.org/wiki/Holy_grail_(web_design)). At the time, I was extremly impressed with my work, and I would still agree that it was technically impressive. I have, however, decided to tear it down in favor of something more simple.

## Iframes
One of my primary goals in my redesign was to have the entire page fit on a single viewport height; sort of like a [single page web app](https://en.wikipedia.org/wiki/Single-page_application). To pull this off, I made use of a central [iframe](https://www.w3schools.com/tags/tag_iframe.asp) of restricted height that would have content loaded into it when hyperlinks in the navbar were clicked by the user.

One thing that sucks about this approach is that the experience of using the website varies significantly depending on the user's resolution. On a very small screen (~720p or less), elements in the sidebars would be forced off screen while the iframe in the center still maintained it's proper height.

You definitely can do iframes correctly, but I definitely did not. here are what I think are some (((better))) examples:

- [PurpleHello98](https://purplehello98.neocities.org/main?z=/frame)
- [MelonKing](https://melonking.net/melon?z=%2Fhome)

Although, to push back on these two sites, they are both virtually unuseable in non-gui browsers (ie. [Lynx](https://en.wikipedia.org/wiki/Lynx_(web_browser)), [w3m](https://w3m.sourceforge.net/)). To offer some conjecture, if your website can't reasonably be used in such a browser, it's probably too complicated.

> "If it doesn’t work in Lynx, it doesn’t work at all." - [Starbreaker](https://starbreaker.org/blog/tech/personal-web-accessibility-march-2024/index.html)

## JavaScript
So like, I'm not as autistic about JavaScript usage as some other people on the Personal Web™, but I definitely like to follow the philosophy of "less is more". One of the great disadvantages of having an iframe dependent layout is that even quite basic functionality of your website becomes JavaScript dependent.

Lets say, for example, you wanted to link somebody to a blog post I had written. The process of doing this is already more complicated than normal, requiring you to right click on the frame and open it in a new tab. There is, however, an additional problem; On the page you've just opened there is not background color; which means white text is being shown on a white background. What a mess!

The reason for this is that the page in question has to have a blank background so that when it is loaded into the iframe, it appears seamless. 

But in this context, it's  all wrong, so to combat this I wrote a bit of JavaScript to detect if the page was being accessed from an iframe, and, if it was not, to change the background color to something sensible like gray. 

Now, in my opinion, that is a pretty ingenious solution, somebody should definitely pay me for it, but it does raise some questions about the efficacy of the "single page application" I was going for. Have I not just created this problem for myself? A problem which did not previously exist? A problem which is only solved with JavaScript? 

This was not an isolated problem either, for a second example, if I wanted the iframe to start with a particular page loaded (so that I may link somebody a page other than the default one), I would need to use JavaScript to read a tag in the URL and change the page loaded into the iframe.

## What next?

Okay, so I've explained the problems I had with my previous site, but what now? Is your new design not too radical in the other direction? 

In some ways it definitely is. Besides the use of [gruvbox](https://github.com/morhetz/gruvbox), there is very little character in the visuals of the site. 

But for all the visual character I've given up, I hope to replace it with substance. I'd like to have more on my website that hyperlinks and images. I'd like to put a lot more focus on writing. As such, I hope the design of my site somewhat reflects that.

Here are some sites I took queues from when designing my new site:

- [Risingthumb](https://risingthumb.xyz/)
- [Starbreaker](https://starbreaker.org/)
- [Commodorian](https://commodorian.org/blog/index.html)

## Plus Something

You might be under the impression that I've used an SSG to make my new site. truth be told, I just use a shell script to keep the navbar on each page the same. You'll never re-cuck me into using HUGO.
