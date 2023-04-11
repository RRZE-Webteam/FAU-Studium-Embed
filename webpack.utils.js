const glob = require('glob');

// methods
const getFilePaths = (pathArr) => {
    let filePaths = [];
    for (let path of pathArr) {
        const files = glob.sync(path);
        filePaths = filePaths.concat(files);
    }
    return filePaths;
};

const getFilesPathObjects = (sourceFolderRelPath, pathArr, type, cb = null) => {
    const filesPathsObjs = [];
    for (let path of pathArr) {
        const src = path;
        const nameWithExtension = path.replace(sourceFolderRelPath + '/', '');
        let name = nameWithExtension.replace(/\.[^/.]+$/, '');
        if (type === 'css') {
            name = name.replace(/^scss/, 'css');
        }
        if (cb) {
            name = cb(name, type);
        }
        filesPathsObjs.push({
            type,
            src,
            name,
        });
    }
    return filesPathsObjs;
};

const addEntriesToEncore = (asset, Encore) => {
    switch (asset.type) {
        case 'js':
            Encore.addEntry(asset.name, asset.src);
            break;
        case 'css':
            Encore.addStyleEntry(asset.name, asset.src);
            break;
        default:
            break;
    }
};

module.exports= {
    getFilePaths,
    getFilesPathObjects,
    addEntriesToEncore
}
