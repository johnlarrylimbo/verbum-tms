import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
		'./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
		 './storage/framework/views/*.php',
		 './resources/views/**/*.blade.php',
		 "./vendor/robsontenorio/mary/src/View/Components/**/*.php"
	],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: '#2563eb',  // blue-600
                danger: '#dc2626',   // red-600
                success: '#16a34a',  // green-600
                warning: '#f59e0b',  // yellow-400
                disabled: '#606060',  // yellow-400
                enabled: '#bc0b43',  // yellow-400
            },
        },
    },

    plugins: [
		forms,
		require("daisyui")
	],

};
