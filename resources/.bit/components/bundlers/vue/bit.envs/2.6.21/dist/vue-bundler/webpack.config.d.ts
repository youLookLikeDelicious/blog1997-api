import Vinyl from 'vinyl';
declare const getConfig: (files: Vinyl[], distPath: string) => {
    entry: {
        [key: string]: any;
    };
    context: string;
    resolve: {
        modules: string[];
        extensions: string[];
    };
    resolveLoader: {
        modules: string[];
    };
    module: {
        rules: ({
            test: RegExp;
            loader: string;
            options: {
                transpileOptions: {
                    transforms: {
                        modules: boolean;
                    };
                };
                babelrc?: undefined;
                presets?: undefined;
                appendTsSuffixTo?: undefined;
            };
            exclude?: undefined;
            use?: undefined;
        } | {
            test: RegExp;
            loader: string;
            options: {
                babelrc: boolean;
                presets: any[];
                transpileOptions?: undefined;
                appendTsSuffixTo?: undefined;
            };
            exclude?: undefined;
            use?: undefined;
        } | {
            test: RegExp;
            loader: string;
            exclude: RegExp;
            options: {
                appendTsSuffixTo: RegExp[];
                transpileOptions?: undefined;
                babelrc?: undefined;
                presets?: undefined;
            };
            use?: undefined;
        } | {
            test: RegExp;
            use: string[];
            loader?: undefined;
            options?: undefined;
            exclude?: undefined;
        } | {
            test: RegExp;
            loader: string[];
            options?: undefined;
            exclude?: undefined;
            use?: undefined;
        })[];
    };
    plugins: any[];
    externals: any[];
    output: {
        path: string;
        filename: string;
        libraryTarget: "umd";
    };
};
export default getConfig;
