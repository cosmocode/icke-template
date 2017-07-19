/* globals process */

// production rules
const prod = {
    'no-magic-numbers': ['warn', { 'ignore': [0, 1, -1]}],
    'no-console': ['error', {'allow': ['warn', 'error']}],
    'strict': ['error', 'function'],

    // // stylistic issues
    'indent': ['error', 4],

    // es6 these are required in airbnb-base, but don't work in IE 11
    'no-var': 0,
    'object-shorthand': ['error', 'never'],
    'prefer-rest-params': 0,
    'prefer-arrow-callback': 0,
    'prefer-template': 0,
    'no-await-in-loop': 0,
    'no-return-await': 0,
    // trailing comma in functions not supported in ES5
    'comma-dangle': ['error', {'arrays': 'always-multiline', 'objects': 'always-multiline', 'functions': 'never'}],
};

// dev rules extend production rules
const dev = Object.assign(
    {},
    prod,
    {
        'no-console': 0,
    }
);

// decide which rules to use -- default to dev
let rules = dev;
if(process.env.NODE_ENV === 'production') {
    rules = prod;
}

module.exports = {
    'extends': 'airbnb-base',
    'parserOptions': {
        'sourceType': 'script', // airbnb-base sets this to module
    },
    'env': {
        'browser': true,
        'es6': true,
    },
    'globals': {
        'JSINFO': false,
        'LANG': false,
        'jQuery': false,
        'createPicker': false,
        'DOKU_BASE': false,
        'pickercounter': true,
        'pickerToggle': false,
        'pickerInsert': false,
        'QUnit': false
    },
    rules
};
