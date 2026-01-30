+++
date = '2023-06-30'
draft = false
title = 'How I Created the Travelers of Agora Road Webring'
summary = 'The Travelers of Agora Road webring has grown to 20 members, so I thought I would explain the process of creating it, along with the resources I used.'
+++

# How I Created the Travelers of Agora Road Webring

The Travelers of Agora Road webring has grown to 20 members, so I thought I would explain the process of creating it, along with the resources I used.  

It started when [Some\_Porcupine](https://forum.agoraroad.com/index.php?members/%E9%9B%A8%E3%80%81some_porcupine%E3%80%81-%E9%80%83%E3%81%92%E5%87%BA%E3%81%97%E3%81%9F%E5%BE%8C.5424/) on Agora asked if I wanted to start a webring. I certainly did, but I also knew nothing about how I might go about doing such a thing. I had various concerns, like: What would we call this webring? What infrastructure would be required to get it working? What criteria would be necessary to join? Where are we getting our graphics from?  

I impulsively suggested the name “Travelers of Agora Road”. I don’t regret that name, but I do wonder if it was the best choice. As risingthumb pointed out, it bears a lot of similarity to the list of Agora users listed on the webring page of the site. I was split between this choice and “Macintosh Cafe Webring”, and in the end decided on the former.  

The webring system itself could have brought this project to its knees. Fortunately, a very simple framework for this sort of thing already exists called [‘onionring.js’](https://garlic.garden/onionring/). Due to this existing script, I managed to get a basic implementation of the webring done in about 20 minutes on the morning I started.  

![](https://forum.agoraroad.com/index.php?attachments/screenshot_20230613_075953-png.64838/)  

Wonderful! It is, however, still quite ugly; a problem which needed to be addressed immediately. Porcupine then came up with this mockup, which inspired the final design.  

![](https://forum.agoraroad.com/index.php?attachments/1686669458919-png.64854/)  

After about an hour of fooling around with the widget CSS. I got to our final design:  

![](https://forum.agoraroad.com/index.php?attachments/screenshot_20230613_140631-png.64886/)  

With that out of the way, we needed to decide on admission requirements, and potential rules for the webring. An initially proposed rule was zero politics, but this was scraped due to a lack of clarity on what exactly was ‘political’. We decided to trust our members to be reasonable.  

I-330 (chad that he is), decided to reimplement the JS components of the webring in PHP. Check out his [blog post](https://i330.dev/posts/no_js/) about it.  

Finally, I had the idea of aggregating our member’s RSS feeds into a single feed for convenience. Sizeofcat made a script for me that creates a dynamically generated OPML file for this purpose. [Read about that here.](https://sizeof.cat/so.cl/1687262601/)  

Thank you to all the members, I really enjoy being a ringmaster and hope more great things will come out of this.

* * *

If you are interested in joining, check out the [index](https://voicedrew.xyz/wr/tag.html) page for further instruction.
