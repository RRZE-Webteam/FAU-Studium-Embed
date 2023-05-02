# Degree programs search shortcode

To render the degree programs search, use the `[fau-studium display="search"]` shortcode.
See below for a list of supported attributes.

## Attributes

| Attribute       | Description                                                               | Required | Default | Valid options     |
|-----------------|---------------------------------------------------------------------------|----------|---------|-------------------|
| `display`       | Entry point for degree programs search rendering.                         | yes      |         | `search`          |
| `lang`          | Language                                                                  | no       | `de`    | `de` or `en`      |
| `output`        | Output mode                                                               | no       | `list`  | `list` or `tiles` |
| `filters`       | Comma-separated list of filters to be shown to visitors.                  | no       |         | See list below    |
| `[filter-name]` | Pre-applied filters with comma-separated term slug or name as their value | no       |         | See list below    |

## List of supported filters

| Filter                  | Description                                                             | Accepted values (if used as a pre-applied filter) |
|-------------------------|-------------------------------------------------------------------------|---------------------------------------------------|
| `admission-requirement` | Admission requirements filter                                           | Not Available                                     |
| `area-of-study`         | Area of study filter                                                    | Comma-separated term slug or name                 |
| `attribute`             | Attribute filter                                                        | Comma-separated term slug or name                 |
| `degree`                | Degree filter                                                           | Comma-separated term slug or name                 |
| `faculty`               | Faculty filter                                                          | Comma-separated term slug or name                 |
| `search`                | Search keyword filter (explicitly added by default, no need to include) | Not Available                                     |
| `semester`              | Semester filter                                                         | Comma-separated term slug or name                 |
| `study-location`        | Study location filter                                                   | Comma-separated term slug or name                 |
| `subject-group`         | Subject group filter                                                    | Comma-separated term slug or name                 |
| `teaching-language`     | Teaching language filter                                                | Comma-separated term slug or name                 |

## Usage

Include a comma-separated list of filters in the `filters` attribute to specify which filters should be shown to
visitors.

### Example without pre-applied filters

```
[fau-studium display="search" output="list" filters="teaching-language,semester,study-location,area-of-study,faculty,degree,subject-group,attribute,admission-requirement"]
```

### Example with pre-applied filters

To pre-apply filters and hide them from visitors, add an attribute with the name of the filter as the key and a
comma-separated term slugs or names as the value.
Example: To pre-apply the `degree` filter with `Bachelor` and `Masters` and also add `faculty` and `study-location` as
visible filters, use the following shortcode:

```
[fau-studium display="search" filters="degree,faculty,study-location" degree="Bachelor,Master"]
```

Note that in this example, although `degree` is a hidden filter, it still must be included in the `filters` attributes.

## Advanced filters

If a shortcode includes more than three visible filters in the `filters` attribute, the first three will be outputted in
the top filter bar while the rest will be hidden in the **"Advanced filters"** dropdown.