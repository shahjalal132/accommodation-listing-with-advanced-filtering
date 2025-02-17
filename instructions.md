**The placeholder text "Search Accommodation" has been updated.**

**Instructions:**

**QA: How do I update the wording on this page, specifically the wording above the filter results?**  
**Ans:** You can update the wording on this page by using Elementor. Click on "Edit with Elementor" and make the necessary changes. Refer to this screenshot for guidance: [Screenshot](https://prnt.sc/FadmM1jBVrcj).

**QA: If I want to add another filter, what is the best way to include it in the filter bar?**  
**Ans:** You will need some coding knowledge. After adding a term from the dashboard (refer to this screenshot: [Screenshot](https://prnt.sc/R48zh7Z1S13z)), you will need to update my plugin. [Screenshot](https://prnt.sc/wTlhhZoQhelg) Duplicate the existing `div` in the plugin file and modify it to match the newly added term. Here’s an example of the code structure:

```html
<div class="form-check">
    <input type="checkbox" id="{term-slug}" class="form-check-input filter-checkbox" name="building_name[]" value="{term-slug}">
    <label class="form-check-label" for="{term-slug}">{term-name}</label>
</div>
```

For reference, here is the term slug for "1 Bedroom": [Screenshot](https://prnt.sc/sfET0790EdfR). Follow the same process for other terms.
