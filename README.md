# A11yFirst Plugins for CKEditor

This module adds the [A11yFirst CKEditor plugins](https://a11yfirst.library.illinois.edu/) to Drupal’s CKEditor.

**Note:** This is experimental, and not governed by A11yFirst. Use at your own risk.

Specifically, the source code of the plugins is copied from their [official distribution](https://github.com/a11yfirst/distribution) and included in this repo, since they are not otherwise independently available. This isn’t ideal, but it (mostly) works until someone comes up with a better plan.

## TODO

Drupal-specific documentation about what’s going on. Drupal likes to get its hooks all over CKEditor config, so a lot of things that are managed internally in the official distribution become external with this integration. This is also not ideal, but it’s far easier than replacing the editor entirely (which would have the same problems, but for all the other config also).

Heading button highlights but doesn’t show ✓ for selected level.

All button and dialog styles are different from [A11yFirst’s sample](https://a11yfirst.library.illinois.edu/demo/distribution/custom/a11yfirst.html).

Implement some tests, maybe?

Figure out a better long-term home for this code. If that doesn’t include being a contrib module at drupal.org, figure out how to make Composer find it.
