require('@babel/register');

// Load our ES Modules Gulpfile (gulpfile.esm.js)
require('./gulpfile.esm.js');

import gulp from 'gulp';
export const hello = (cb) => {
  console.log('First Task');
  cb();
}

export const promise = (cb) => {
  return new Promise((resolve, reject) => {
    setTimeout(() => {
      resolve();
    }, 300);
  });
};

export default hello
