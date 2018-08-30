<v-touch class="card"
    :class="{'pdf-prev': selectedFileIs('pdf') || selectedFileIs('text')}"
    @swiperight="goToPrev()"
    @swipeleft="goToNext()">

    <div class="card-image">
        <template v-if="selectedFileIs('pdf') || selectedFileIs('text')">
            <object :data="selectedFile.path" :type="selectedFile.type" width="100%" height="100%">
                 <p v-if="selectedFileIs('pdf')">{{ trans('MediaManager::messages.pdf') }}</p>
            </object>
        </template>

        {{-- video --}}
        <div v-else-if="selectedFileIs('video')">
            <video controls
                playsinline
                preload="auto"
                data-player
                :src="selectedFile.path">
                {{ trans('MediaManager::messages.video_support') }}
            </video>
        </div>

        {{-- audio --}}
        <div v-else-if="selectedFileIs('audio')" class="audio-prev">
            <img v-if="selectedFilePreview"
                :src="selectedFilePreview.picture ? selectedFilePreview.picture : selectedFilePreview"
                :alt="selectedFile.name"
                class="image"/>
            <audio controls
                class="is-hidden"
                preload="auto"
                data-player
                :src="selectedFile.path">
                {{ trans('MediaManager::messages.audio.support') }}
            </audio>
        </div>

        <template v-else>
            <a :href="selectedFile.path"
                target="_blank"
                rel="noreferrer noopener"
                class="image">
                <img :src="selectedFilePreview" :alt="selectedFile.name">
            </a>
        </template>
    </div>

    <div class="card-content">
        <div class="media">
            <div class="media-content">
                {{-- name --}}
                <p class="title is-marginless">
                    <span class="link"
                        @click="copyLink(selectedFile.path)"
                        :title="linkCopied ? trans('copied') : trans('to_cp')"
                        v-tippy="{arrow: true, hideOnClick: false, followCursor: true}"
                        @hidden="linkCopied = false">
                        @{{ selectedFile.name }}
                    </span>

                    {{-- open url --}}
                    <a :href="selectedFile.path"
                        v-if="selectedFileIs('pdf') || selectedFileIs('text')"
                        target="_blank"
                        rel="noreferrer noopener"
                        title="{{ trans('MediaManager::messages.public_url') }}"
                        v-tippy>
                        <icon name="search" scale="1.1"></icon>
                    </a>
                </p>

                {{-- date --}}
                <p class="subtitle is-6 m-t-5">@{{ selectedFile.last_modified_formated }}</p>

                {{-- ops --}}
                <p>
                    {{-- lock / unlock --}}
                    <span v-if="$refs.lock"
                        class="icon is-large link"
                        :class="IsLocked(selectedFile) ? 'is-danger' : 'is-success'"
                        :title="IsLocked(selectedFile) ? '{{ trans('MediaManager::messages.unlock') }}': '{{ trans('MediaManager::messages.lock') }}'"
                        v-tippy="{arrow: true, hideOnClick: false}"
                        @click="$refs.lock.click()">
                        <icon :name="IsLocked(selectedFile) ? 'lock' : 'unlock'" scale="1.2"></icon>
                    </span>

                    {{-- visibility --}}
                    <span v-if="$refs.vis"
                        class="icon is-large link"
                        :class="IsVisible(selectedFile) ? 'is-success' : 'is-danger'"
                        title="{{ trans('MediaManager::messages.visibility.set') }}"
                        v-tippy
                        @click="$refs.vis.click()">
                        <icon :name="IsVisible(selectedFile) ? 'eye' : 'eye-slash'" scale="1.2"></icon>
                    </span>
                </p>
            </div>

            <div class="media-right has-text-centered">
                <div>
                    {{-- download --}}
                    <button class="button btn-plain"
                        @click.prevent="saveFile(selectedFile)"
                        v-tippy
                        title="{{ trans('MediaManager::messages.download.file') }}">
                        <span class="icon"><icon name="download" scale="4"></icon></span>
                    </button>

                    {{-- size --}}
                    <p>@{{ getFileSize(selectedFile.size) }}</p>
                </div>
            </div>
        </div>
    </div>

    <footer class="card-footer">
        {{-- move --}}
        <div class="card-footer-item">
            <button class="button btn-plain is-fullwidth"
                :disabled="item_ops() || !checkForFolders || isLoading"
                @click="moveItem()">
                <span class="icon is-small"><icon name="share"></icon></span>
                <span>{{ trans('MediaManager::messages.move.main') }}</span>
            </button>
        </div>

        {{-- rename --}}
        <div class="card-footer-item">
            <button class="button btn-plain is-fullwidth"
                :disabled="item_ops() || isLoading"
                @click="renameItem()">
                <span class="icon is-small"><icon name="terminal"></icon></span>
                <span>{{ trans('MediaManager::messages.rename.main') }}</span>
            </button>
        </div>

        {{-- editor --}}
        <div class="card-footer-item" v-if="selectedFileIs('image')">
            <button class="button btn-plain is-fullwidth"
                :disabled="item_ops() || isLoading"
                @click="imageEditorCard()">
                <span class="icon"><icon name="object-ungroup" scale="1.2"></icon></span>
                <span>{{ trans('MediaManager::messages.editor') }}</span>
            </button>
        </div>

        {{-- delete --}}
        <div class="card-footer-item">
            <button class="button btn-plain is-fullwidth"
                :disabled="item_ops() || isLoading"
                @click="deleteItem()">
                <span class="icon is-small"><icon name="trash" scale="1.2"></icon></span>
                <span>{{ trans('MediaManager::messages.delete.main') }}</span>
            </button>
        </div>
    </footer>
</v-touch>
