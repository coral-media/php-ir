# coral-media/php-ir

A focused PHP library implementing classical Information Retrieval algorithms
based on the Stanford IR book 
[Introduction to Information Retrieval](https://nlp.stanford.edu/IR-book/html/htmledition/irbook.html).

## License
MIT

## Scope
- Vector space model
- Similarity measures (cosine, euclidean)
- TF / IDF / TF-IDF weighting
- Clustering (K-Means, variants)
- Deterministic, explainable algorithms

## Non-Goals
- NLP pipelines
- Transformers / embeddings
- Dataset abstractions
- Framework integrations

## Philosophy
This library favors:
- Mathematical correctness
- Explicit abstractions
- Deterministic behavior
- Native acceleration via Zephir (optional)

## Releasing

Versions are bumped using:

    ./scripts/bump-version.sh
    git commit -am "chore: bump version"

After committing the bump, create and push a tag:

    git tag vX.Y.Z
    git push origin main --tags

### Git Hooks (Optional)

This repository provides optional Git hooks to help enforce
versioning and release discipline.

To enable them locally:

    ./scripts/install-hooks.sh

Hooks are opt-in and not required to contribute.
