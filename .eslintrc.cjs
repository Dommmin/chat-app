module.exports = {
    root: true,
    parserOptions: {
        parser: '@babel/eslint-parser',
        requireConfigFile: false,
        ecmaVersion: 2023,
        sourceType: 'module',
    },
    env: {
        browser: true,
        amd: true,
        node: true,
    },
    extends: [
        'plugin:vue/vue3-essential',
        'eslint:recommended',
        'plugin:prettier/recommended',
    ],
    plugins: ['prettier'],
    rules: {
        'no-console': process.env.NODE_ENV === 'production' ? 'warn' : 'off',
        'no-debugger': process.env.NODE_ENV === 'production' ? 'warn' : 'off',
        'no-undef': 'warn',
        'no-unused-vars': 'warn',
        'vue/multi-word-component-names': 'off',
        'vue/no-mutating-props': 'off',
    },
};
