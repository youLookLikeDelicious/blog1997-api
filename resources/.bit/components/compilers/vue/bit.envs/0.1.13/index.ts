import '@vue/cli-plugin-babel';
import '@vue/cli-plugin-eslint';
import '@vue/cli-plugin-typescript';
import 'eslint';
import 'eslint-loader';
import 'eslint-plugin-prettier';
import 'eslint-plugin-vue';
import 'postcss-loader';
import 'recursive-readdir';
import 'url-loader';
import 'vue';
import 'vue-property-decorator';
import 'vue-template-compiler';

import path from 'path';
import debug from 'debug';
import { promises as fs } from 'fs';
import Vinyl from 'vinyl';

import { TSConfig } from './tsconfig';
import { vueConfig } from './vueConfig';

import {
  CompilerContext,
  BuildResults,
  createCapsule,
  destroyCapsule,
  getSourceFiles,
  readFiles,
} from '@bit/bit.envs.compilers.utils';

// eslint-disable-next-line @typescript-eslint/no-var-requires
const vueCli = require('@vue/cli-service');

if (process.env.DEBUG) {
  debug('build');
}
const COMPILED_EXTS = ['vue', 'ts', 'tsx'];

export function getDynamicPackageDependencies(): Record<string, any> {
  return {};
}

export function getDynamicConfig(ctx: CompilerContext): Record<string, string> {
  return ctx.rawConfig;
}

export async function action(ctx: CompilerContext): Promise<BuildResults> {
  // build capsule
  const { res, directory } = await createCapsule(ctx.context.isolate, { shouldBuildDependencies: true });
  const distDir = path.join(directory, 'dist');
  const componentObject = res.componentWithDependencies.component.toObject();
  const { files, mainFile, name } = componentObject;

  // write TS config into capsule
  let sources: Vinyl[] = getSourceFiles(files, COMPILED_EXTS);
  let TS = Object.assign(TSConfig, {
    include: sources.map((s) => s.path),
  });
  await fs.writeFile(path.join(directory, 'tsconfig.json'), JSON.stringify(TS, null, 4));

  //write Vue config into capsule
  await fs.writeFile(path.join(directory, 'vue.config.js'), `module.exports=${JSON.stringify(vueConfig)}`);

  try {
    const service = new vueCli(directory);
    await service.run('build', {
      mode: 'development',
      entry: path.resolve(directory, mainFile),
      target: 'lib',
      name,
      dest: 'dist',
    });
  } catch (e) {
    console.log(e);
    process.exit(1);
  }

  //get dists
  const dists = await readFiles(distDir);

  destroyCapsule(res.capsule);

  return {
    mainFile: `${name}.common.js`,
    dists: dists || [],
    packageJson: {
      browser: `${name}.umd.js`,
    },
  };
}
