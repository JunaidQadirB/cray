#!/bin/bash

set -e

Red='\e[0;31m'
Green='\e[0;7;32m'
Yellow='\e[0;33m'
Purple='\e[0;35m'
NC='\e[0m' # No Color

current_version=''

bump_revision() {
    ver=$current_version
    major=$(echo $ver | cut -d. -f1)
    minor=$(echo $ver | cut -d. -f2)
    revision=$(echo $ver | cut -d. -f3)
    meta=$(echo $ver | cut -d- -f4)

    next_release="$major.$minor.$revision"
}

start_prompt() {
    while [[ ! "${response,,}" =~ ^y|yes|n|no$ ]]; do
        read -p "This will create a new release and deploy to production. Continue(y|yes/n|no)?" response
        if [[ "${response,,}" =~ ^y|yes$ ]]; then
            echo -e "Starting release process..."
            echo ""
        elif [[ "${response,,}" =~ ^n|no$ ]]; then
            echo "Exiting..."
            exit 1
        fi
    done
}

set_changelog() {
    if [[ $# -eq 0 ]]; then
        release_message="# In this release
$(git log --oneline $current_version..@ --pretty=format:"- %s (%h)" --abbrev-commit | sort)"
        echo -e "$release_message"
        echo ""
    fi
}

version_prompt() {
    echo -e "Current released version is:\e[2;7;33m $current_version ${NC}"
    echo ""
    read -p "$(echo -e "Enter next version number. Ctrl+C to cancel. Enter to Continue. [Detected: ${Green} $next_release ${NC}]: ")" user_entered_version

    if [[ ! -z "${user_entered_version// /}" ]]; then
        next_release=$user_entered_version
    fi
}

check_version_format() {
    if [[ $next_release != v* ]]; then
        next_release="v$next_release"
    fi

    if [[ $next_release =~ ^v((([0-9]+)\.([0-9]+)\.([0-9]+)(?:-([0-9a-zA-Z-]+(?:\.[0-9a-zA-Z-]+)*))?)(?:\+([0-9a-zA-Z-]+(?:\.[0-9a-zA-Z-]+)*))?)$ ]]; then
        if [[ "$should_continue" == true ]]; then
            echo -e "${Yellow}Next release version will be $next_release${NC}"
        fi
        version_format_ok=true
    else
        version_format_ok=false
        echo -e "${Red}Error: Please make sure version follows semver${NC}"
    fi
}

start_prompt

current_version="$(git tag --sort=v:refname | tail -1)"

next_release=''

version_format_ok=false

should_continue=false

set_changelog

bump_revision

version_prompt

check_version_format

if [ "$version_format_ok" = true ]; then
    vendor/bin/pest

    git release -m"$release_message" -v"$next_release"

    echo""
    echo "Please check your GitHub actions for more information about the status of the release."
fi
