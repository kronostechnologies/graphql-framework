#!/bin/bash
set -e -u

# Update & Pull 1st
git pull

_usage() {
    cat << EOF

    Usage: ${0##*/} [ACTION]

    Action     Description

    patch      Tag version to next available patch (0.0.x)
    minor      Tag version to next available minor (0.x.0)
    major      Tag version to next available major (x.0.0)
    sync       Delete all local tags and fetch the ones from remote

$(_usage_current)

EOF
}

patch() {
    sync
    VNUM3=$((VNUM3+1))
    _create_tag
    _push_tag
}

minor() {
    sync
    VNUM2=$((VNUM2+1))
    VNUM3=0
    _create_tag
    _push_tag
}

major() {
    sync
    VNUM1=$((VNUM1+1))
    VNUM2=0
    VNUM3=0
    _create_tag
    _push_tag
}

sync() {
    git tag -d $(git tag -l)
    git fetch

    # Reference: https://stackoverflow.com/a/27332476
    # Get highest tag number
    VERSION=`git describe --abbrev=0 --tags`

    # Replace . with space so can split into an array & remove v too
    VERSION_BITS=(${VERSION#?})
    VERSION_BITS=(${VERSION_BITS//./ })

    # Fetch versions
    VNUM1=${VERSION_BITS[0]}
    VNUM2=${VERSION_BITS[1]}
    VNUM3=${VERSION_BITS[2]}
}

_create_tag() {
    NEW_TAG="v$VNUM1.$VNUM2.$VNUM3"
    COMMIT_NUM=$(git rev-parse HEAD)

    echo "Tagged $COMMIT_NUM with $NEW_TAG"
    git tag $NEW_TAG
}

_push_tag() {
    git push --tags
}

if [ -z "${1+x}" ]; then
    _usage
    exit 1
fi

"$@"
