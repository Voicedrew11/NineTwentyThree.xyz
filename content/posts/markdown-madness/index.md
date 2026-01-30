+++
date = '2023-09-03'
draft = false
title = 'Markdown Madness'
summary = 'Overengineerd python script that basically does the same thing as a static site generator anyways'
+++

# Markdown Madness

If you didn't know, I write these blog posts in markdown: An extreamly simple markup language that allows you to format text while also leaving it 'human readable'.

Since I began, I have been copying my markdown files into an online html coverter, copying a previous blog post's html file to use as a template, and then copying my converted html into the file. This was extreamly annoying, and often lead to mistakes on my part. I wrote a python script to deal with this problem

```python
import markdown
import sys
import os

if len(sys.argv) != 2:
    print("Usage: python build-article.py input.md")


input_file = sys.argv[1] 
try:
    with open(input_file, "r") as file:
        markdown_text = file.read()

    output_file = os.path.splitext(input_file)[0] + ".html"

    html = markdown.markdown(markdown_text)

    lines = markdown_text.split('\n')
    if lines:
        first_line = lines[0].lstrip('# ').strip() 
    else:
        first_line = "No content in the Markdown file."

    final_html = f"""
<!DOCTYPE html>
<html lang="">

<head>
    <meta charset="utf-8">
    <title>{first_line}</title>
    <link rel="stylesheet" href="meta.css">
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico">
</head>

<header>
    <a href="https://voicedrew.xyz/"><img src="https://voicedrew.xyz/images/bannerT.png"></a>
</header>

    <body>
    <div  class="foreground">
        <article>
            {html}
        </article>
    </div>

<footer><h1><a href="index.html">◄ Back</a></h1></footer>
    </body>
</html>
"""

    with open(output_file, "w") as file:
        file.write(final_html)

    print(f"HTML content written to '{output_file}'.")

except FileNotFoundError:
    print(f"Error: File '{input_file}' not found.")
```

Super simple right? It takes in a markdown file and spits out a complete [voicedrew.xyz](https://voicedrew.xyz/) blog post. Header image and all! Of course, feel free to modify this script and use it however you want.

---

https://github.com/Voicedrew11/build-article/blob/main/build-article.py
