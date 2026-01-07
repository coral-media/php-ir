# Changelog

## [v0.7.1](https://github.com/coral-media/php-ir/releases/tag/v0.7.1)

- [22ffc7b](https://github.com/coral-media/php-ir/commit/22ffc7b3feef8ee46a0b414bdba2254c7e7e148d) feat(vocabulary): add minimum document frequency threshold

## [v0.7.0](https://github.com/coral-media/php-ir/releases/tag/v0.7.0)

- [c9f0152](https://github.com/coral-media/php-ir/commit/c9f01525c0060de041b04c5456ec3f87bd86661b) chore(release): bump version to 0.7.0
- [45d3147](https://github.com/coral-media/php-ir/commit/45d31473bf14efb9e250b881b7f436ab0c0b146f) fix(ir): make cosine similarity IR-correct and normalize stopwords post-stemming

## [v0.6.5](https://github.com/coral-media/php-ir/releases/tag/v0.6.5)

- [f5c8e6b](https://github.com/coral-media/php-ir/commit/f5c8e6bd6eed7e0c11ca95576e2dfafbdb9aeb96) chore(release): bump version to 0.6.5
- [bc8a1a8](https://github.com/coral-media/php-ir/commit/bc8a1a8fbceff0cf7b711e160024d674a4a4d93f) fix(distance): allow sparse × dense cosine similarity in clustering
- [3713be8](https://github.com/coral-media/php-ir/commit/3713be8c854d7670b949816306eba62900a7adcc) fix(distance): optimize cosine similarity for sparse × dense vectors

## [v0.6.4](https://github.com/coral-media/php-ir/releases/tag/v0.6.4)

- [460cd95](https://github.com/coral-media/php-ir/commit/460cd95c3b47411ce19ca4612586d79d105a8ad3) chore(release): bump version to 0.6.4
- [9375e29](https://github.com/coral-media/php-ir/commit/9375e29a83caa1366315e3098fb13467f99d5e19) fix(vector): support iterable sparse corpora in dense collection factory
- [da27faf](https://github.com/coral-media/php-ir/commit/da27faf36060511e8f283e5090fa4a65496adb07) chore(ir-pipeline): harden stemming normalization and test infrastructure
- [653e8c8](https://github.com/coral-media/php-ir/commit/653e8c8fe9979d6551462c7e099e34801faf2a94) refactor(core): tighten immutability, clean quality and centroid implementations
- [5e348aa](https://github.com/coral-media/php-ir/commit/5e348aaac208f2376744a6ad2cdba2f88e6c3d04) feat(clustering): add entropy metrics to cluster quality evaluation
- [94084cb](https://github.com/coral-media/php-ir/commit/94084cb4b344066275cc193dc7dda0765d36b835) chore(docs): added info badges
- [fd707b0](https://github.com/coral-media/php-ir/commit/fd707b0adc1b6d0dae4755662c60c7bbfe8eda03) docs: add descriptive README with IR foundations and library overview

## [v0.6.3](https://github.com/coral-media/php-ir/releases/tag/v0.6.3)

- [22e5d51](https://github.com/coral-media/php-ir/commit/22e5d513a8a06ef7ae1d1084fe7efac43e812ad8) chore(release): bump version to 0.6.3
- [99c4a98](https://github.com/coral-media/php-ir/commit/99c4a9805d76ff84bded1036965f998fc462a7c3) feat(clustering): add cluster quality evaluation and median robustness test

## [v0.6.2](https://github.com/coral-media/php-ir/releases/tag/v0.6.2)

- [0fab7ff](https://github.com/coral-media/php-ir/commit/0fab7ff14b757d2b54e2eeceb02cb8a772f788b3) chore(release): bump version to 0.6.2
- [cdc8913](https://github.com/coral-media/php-ir/commit/cdc8913c4e5b10fd2d4dfec2e689dc20383ef48e) refactor: centralize sparse-to-dense corpus conversion and expose vocabulary IDF stats
- [ed81460](https://github.com/coral-media/php-ir/commit/ed81460848a468314469e119e69363e7f1a16ed0) docs(changelog): update for v0.6.1

## [v0.6.1](https://github.com/coral-media/php-ir/releases/tag/v0.6.1)

- [c494d3e](https://github.com/coral-media/php-ir/commit/c494d3eafd427c9a30dc5c56e2b64a54e4eadeb4) chore(release): bump version to 0.6.1
- [ad968b5](https://github.com/coral-media/php-ir/commit/ad968b521d383cc54c5a9f3eabf1e8fee8e5788b) feat(clustering,fixtures): improve movie corpus descriptions and harden spherical median centroid
- [9ce25ba](https://github.com/coral-media/php-ir/commit/9ce25bacab1dc841eff19776cc8199c5a50472c0) fix(clustering): rename factory class for PSR-4 compliance
- [6642d34](https://github.com/coral-media/php-ir/commit/6642d34e9e6aff7e86aa1fbeed9e2d63832a96ea) docs(changelog): update for v0.6.0

## [v0.6.0](https://github.com/coral-media/php-ir/releases/tag/v0.6.0)

- [83bb757](https://github.com/coral-media/php-ir/commit/83bb7576ed609a54b4f65ee77e99fae673cb1fec) chore(release): bump version to 0.6.0
- [962cbe4](https://github.com/coral-media/php-ir/commit/962cbe403ec954fc2737f06e0f4da48cdcfe8766) feat(language): introduce language-specific stopword filtering
- [760629b](https://github.com/coral-media/php-ir/commit/760629b21e11308ba0ae24f79ecd7e427db36ff8) chore(quality): internal cleanup following PHPMD analysis
- [0e3af57](https://github.com/coral-media/php-ir/commit/0e3af57402b0f88db6ef583f13b480a74ba7bc4f) chore(quality): add PHPMD quality tool and ruleset
- [1c96c32](https://github.com/coral-media/php-ir/commit/1c96c327937be87b20467e2cac93b9c712974f26) test: add invariant test for cluster concept extraction
- [e6b8c56](https://github.com/coral-media/php-ir/commit/e6b8c567892a0c561fc4523cc2f3e94905435733) docs(changelog): update for v0.5.4

## [v0.5.4](https://github.com/coral-media/php-ir/releases/tag/v0.5.4)

- [8fe535f](https://github.com/coral-media/php-ir/commit/8fe535f89de9051d8aedf5da0c02729ec65b8741) chore(release): bump version to 0.5.4
- [dfe0207](https://github.com/coral-media/php-ir/commit/dfe0207aac55221c5b8bd5aa5a628933c56b63f8) feat(feature): add stemming normalization with IR stability invariants
- [2635ba1](https://github.com/coral-media/php-ir/commit/2635ba18874ccfd3e84e68771dcd7f1a27f4f0e3) feat(feature): add stemming primitives and adapters
- [d3b561b](https://github.com/coral-media/php-ir/commit/d3b561bbfef467e85928b089d7e8f75906061267) chore(changelog): fixes links in output file
- [cf4fb44](https://github.com/coral-media/php-ir/commit/cf4fb44f49d4ca72c6a45be29a967b65abd04e30) chore(changelog): github friendly links for commits and release tags
- [2b113a6](https://github.com/coral-media/php-ir/commit/2b113a6fe734c2d65cfb15f760f3a7549a858210) chore(changelog): adds changelog generator and first changelog file

## [v0.5.3](https://github.com/coral-media/php-ir/releases/tag/v0.5.3)

- [f608643](https://github.com/coral-media/php-ir/commit/f6086435c3994a5e2acfd71584c1af7158aa6d40) chore(release): bump version to 0.5.3
- [79654cd](https://github.com/coral-media/php-ir/commit/79654cd14657e015bcdbbebaf9baea633bc96339) feat(clustering): add cluster concept extraction from centroids
- [da5699d](https://github.com/coral-media/php-ir/commit/da5699d99cf64dfddbb815bbcfb347b86f17a7ae) fix(clustering): normalize centroids in spherical k-means++ initializer

## [v0.5.2](https://github.com/coral-media/php-ir/releases/tag/v0.5.2)

- [bb04868](https://github.com/coral-media/php-ir/commit/bb048689821c328f2cdc1c8d5ec2362f58ab08d7) chore(release): bump version to 0.5.2
- [491da46](https://github.com/coral-media/php-ir/commit/491da468307256bbc95e811d9ebd9be238cf91cf) feat(clustering): add spherical k-means++ initializer

## [v0.5.1](https://github.com/coral-media/php-ir/releases/tag/v0.5.1)

- [fc21a33](https://github.com/coral-media/php-ir/commit/fc21a33f6bf3e74349a3b5fbd6ec1ed79e7da895) chore(release): bump version to 0.5.1
- [59eb9b8](https://github.com/coral-media/php-ir/commit/59eb9b8b07f89c9983f15563c682682490ea4c44) test(smoke): add end-to-end spherical dot-product TF-IDF search

## [v0.5.0](https://github.com/coral-media/php-ir/releases/tag/v0.5.0)

- [ec1ce45](https://github.com/coral-media/php-ir/commit/ec1ce4538f3a26ed62e213b6c8776856fb67339f) chore(release): bump version to 0.5.0
- [e5f91b6](https://github.com/coral-media/php-ir/commit/e5f91b661feb2ffad90f274cf78ca6b4147e3313) feat(clustering): add spherical k-means support
- [809941a](https://github.com/coral-media/php-ir/commit/809941a18f0e4da5f69f9b199949086c6f03dd1f) feat(distance): add euclidean similarity metric

## [v0.4.1](https://github.com/coral-media/php-ir/releases/tag/v0.4.1)

- [7aa43ec](https://github.com/coral-media/php-ir/commit/7aa43ec2983b28db796d8fc57dad728ac8218895) chore(release): bump version to 0.4.1
- [2c5a85e](https://github.com/coral-media/php-ir/commit/2c5a85eff1cfa18de8a225b8a9fe87eeb1a675ea) test(smoke): add real movie collection fixture and end-to-end search tests
- [d301708](https://github.com/coral-media/php-ir/commit/d3017080ac70a02fa43b2a261ddf35a6582603aa) feat(feature): add text normalization primitives

## [v0.4.0](https://github.com/coral-media/php-ir/releases/tag/v0.4.0)

- [a8a306a](https://github.com/coral-media/php-ir/commit/a8a306a1dea8a151a4b890d5a91c54bb23b26e2f) chore(release): bump version to 0.4.0
- [3f67705](https://github.com/coral-media/php-ir/commit/3f677055013fac5182801b67605e021e0f18480a) feat(serialization): add typed serializers for IR artifacts

## [v0.3.0](https://github.com/coral-media/php-ir/releases/tag/v0.3.0)

- [803a6cc](https://github.com/coral-media/php-ir/commit/803a6cc6ca7739945645bf567df96d10dafc7c02) chore(release): bump version to 0.3.0
- [f4f9773](https://github.com/coral-media/php-ir/commit/f4f9773bee875511ea9ae1d6fc48271bc381d369) feat(processing): add minimal processing layer with wiring tests
- [f19bb3a](https://github.com/coral-media/php-ir/commit/f19bb3a0b178d192eea4b4d54936c6b012e36287) docs: update README with BM25, scope, and API stability
- [bfc8e42](https://github.com/coral-media/php-ir/commit/bfc8e42c4855ff2745c16505a7fcb7900221b980) feat(weighting): add BM25 probabilistic ranking model

## [v0.2.0](https://github.com/coral-media/php-ir/releases/tag/v0.2.0)

- [f2d7d77](https://github.com/coral-media/php-ir/commit/f2d7d776c1f5cf078d811d21c503190e842b8594) test(smoke): add end-to-end TF-IDF search functional test
- [a6005a8](https://github.com/coral-media/php-ir/commit/a6005a87d77c39f1e78c8b64dd1b7ef63b66fa39) feat(vector): add sparse vectorizer from term frequencies
- [844e5d0](https://github.com/coral-media/php-ir/commit/844e5d0ce6c01ae4ad425cbfca387b2094fa2b96) feat(feature): add vocabulary builder for corpus statistics
- [5761e10](https://github.com/coral-media/php-ir/commit/5761e105a82c31e1e11b3f85c1e73b4a8d4a8a89) feat(feature): add term frequency extractors
- [107c6d7](https://github.com/coral-media/php-ir/commit/107c6d71eaeb6321559b6ce38ec8e6b2d1aeede7) feat(feature): add tokenizer abstractions for term extraction

