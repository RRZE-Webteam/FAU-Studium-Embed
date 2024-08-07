# Degree programs search shortcode

To render the degree programs search, use the `[fau-studium display="search"]` shortcode.
See below for a list of supported attributes.

## Attributes

| Attribute       | Description                                                        | Required | Default | Valid options     |
|-----------------|--------------------------------------------------------------------|----------|---------|-------------------|
| `display`       | Entry point for degree programs search rendering                   | yes      |         | `search`          |
| `lang`          | Language                                                           | no       | `de`    | `de` or `en`      |
| `hide`          | Comma-separated names of elements that should be hidden            | no       |         | See list below    |
| `output`        | Output mode                                                        | no       | `list`  | `list` or `tiles` |
| `filters`       | Comma-separated list of filters to be shown to visitors            | no       |         | See list below    |
| `ids`           | Comma-separated list of HIS codes                                  | no       |         | See list below    |
| `<filter-name>` | Pre-applied filters with comma-separated term names as their value | no       |         | See list below    |

## List of supported filters

| Filter                                              | Description                                                             | Accepted values (if used as a pre-applied filter) |
|-----------------------------------------------------|-------------------------------------------------------------------------|---------------------------------------------------|
| `admission-requirement`                             | Admission requirements filter                                           | Comma-separated term slugs                        |
| `area-of-study`                                     | Area of study filter                                                    | Comma-separated term names                        |
| `attribute`                                         | Attribute filter                                                        | Comma-separated term names                        |
| `degree`                                            | Degree filter                                                           | Comma-separated term names                        |
| `faculty`                                           | Faculty filter                                                          | Comma-separated term names                        |
| `german-language-skills-for-international-students` | German language skills for international students filter                | Comma-separated term names                        |
| `search`                                            | Search keyword filter (explicitly added by default, no need to include) | Not available                                     |
| `semester`                                          | Semester filter                                                         | Comma-separated term names                        |
| `study-location`                                    | Study location filter                                                   | Comma-separated term names                        |
| `subject-group`                                     | Subject group filter                                                    | Comma-separated term names                        |
| `teaching-language`                                 | Teaching language filter                                                | Comma-separated term names                        |

## List of elements that can be hidden

| Element   | Description                                                  |
|-----------|--------------------------------------------------------------|
| `heading` | Hide the degree program overview title                       |
| `search`  | Hide the search form, including all fields                   |

## Usage

Include a comma-separated list of filters in the `filters` attribute to specify which filters to
show visitors.

### Example without pre-applied filters

```plaintext
[fau-studium display="search" output="list" filters="teaching-language,semester,study-location,area-of-study,faculty,degree,subject-group,attribute,admission-requirement"]
```

### Example with pre-applied filters

To pre-apply filters and hide them from visitors, add an attribute with the name of the filter as the key and
comma-separated term names as the value. Please always use slugs as the value for the admission requirement filter.
Example: To pre-apply the `degree` filter with `Bachelor` and `Master` and also add `faculty` and `study-location` as
visible filters, use the following shortcode:

```plaintext
[fau-studium display="search" filters="degree,faculty,study-location" degree="Bachelor,Master"]
```

Note that in this example, although `degree` is a hidden filter, it still must be included in the `filters` attributes.

Note that on the main website (meinstudium.fau.de), instead of using term names, it is possible to use term IDs or
slugs. This does **not** work on other websites, except for the admission requirement filter, which only works with slugs.

## Advanced filters

If a shortcode includes more than three visible filters in the `filters` attribute, the first three will be outputted in
the top filter bar, while the rest will be hidden in the **"Advanced filters"** dropdown.

### Usage of `ids` attribute

The `ids` attribute takes a comma-separated list of HIS codes, HIS codes can be complete or partial. Valid examples are:

- 08|185|-|-|H|1|E|P|V|1|
- 08|185
- 08|185,08|145,08|185|-|-|H|1|E|P|V|1|

currently, only position 1,2 and 7 of the HIS code are used, other values are ignored, in the first example of above, value `08`, `185` and `E` are used, others are ignored.
