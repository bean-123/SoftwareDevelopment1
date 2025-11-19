# Ohjelmistokehitysprojekti 1 – Networking Website

A custom WordPress project built using the Twenty Twenty-Five theme, extended with dynamic filters, custom queries, and ACF-powered single post templates.

## Overview

This project is a modern, responsive WordPress site created for Ohjelmistokehitysprojekti1 (Business College Helsinki school). The site allows users to explore events and communities through an interactive filtering system on the homepage. It also includes fully customized single post templates displaying event and community details pulled from ACF fields.

The goal was to build a functional, user-friendly, and content-driven platform without relying on page builders — achieving everything using WordPress core features, theme development, PHP, JavaScript, ACF, and custom templates.

![Hero section](/Screenshot%202025-11-19%20at%209.53.12.png)

## Key Features

1. Homepage Filter System

A custom filter bar in the Hero section displays selected tags.

Users can click one or multiple tags to filter:

- Events

- Communities

Filtering instantly updates two separate sections on the homepage:

- EVENTS

- COMMUNITIES

The filtering is handled through a custom JavaScript file loaded only on the homepage.
Each post card receives data-tags attributes dynamically from WP:

<div class="filter-post" data-tags="gaming,helsinki,coding"></div>

2. Custom Shortcodes for Dynamic Content Grids

Two shortcodes were created in functions.php:

    Events Grid
    [filter_events]

    Communities Grid
    [filter_communities]

Each shortcode:

- Queries posts by category (event or community)

- Outputs custom markup

- Embeds post tags into data-tags

- Allows the homepage filter to manipulate the results in real time

This approach makes the grids reusable anywhere on the site.

3. Advanced Custom Fields Integration

Custom fields were created using ACF to store structured data.

- Event fields

- Date

- Time

- Location

- Registration link

- Community fields

- Platform

- Website link

These fields appear _only_ on the relevant post type thanks to ACF’s location rules.

4. Single Post Template Enhancement

A fully custom PHP block was added through functions.php using the the_content filter.
This injects ACF data automatically at the bottom of every event or community post.

The system checks the post’s category:

    $is_event = has_category('event');
    $is_community = has_category('community');

Based on the category, the template outputs the correct ACF fields with clean HTML styling.

This ensures:

- No separate templates are required

- Editors can simply create posts normally

- Every single event/community page shows structured details automatically

## Challenges Solved

- Implemented a filter system that works across two separate loops at once

- Ensured posts dynamically expose their tags through custom PHP

- Fixed limitations of Query Loop blocks by replacing them with custom shortcodes

- Built a universal ACF template that applies only to event and community posts

Integrated everything without modifying the homepage layout design

```
Project Structure
/twentytwentyfive
├── functions.php
├── assets/
├── templates/
├── parts/
├── patterns/
└── styles/
```

All custom PHP and JS used in this project is stored in the theme folder.

    ✔ Fully functional
    ✔ Ready for showcase
    ⚠ Could be converted to a child theme in future if required

Author

Amy Platt

Built as part of Ohjelmistokehitysprojekti 1, demonstrating skills in theme development, PHP, WordPress architecture, and front-end dynamic functionality.
