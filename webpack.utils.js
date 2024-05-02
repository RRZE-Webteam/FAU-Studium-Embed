const pipe =
    (...f) =>
    (v) =>
        f.reduce((res, f) => f(res), v);

const withPostCssConfigRules = (rules) => {
    return rules.map((rule) => {
        if (!Array.isArray(rule.use)) {
            return rule;
        }

        rule.use.forEach((item) => {
            if (!item.loader) {
                return;
            }

            if (item.loader.match('postcss-loader')) {
                item.options = {
                    ...(item?.options || {}),
                    postcssOptions: { config: __dirname + '/postcss.config.js' },
                };
            }
        });

        return rule;
    });
};

exports.modifyRules = (rules) => {
    return pipe(withPostCssConfigRules)(rules);
};
