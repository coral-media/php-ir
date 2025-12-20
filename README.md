# coral-media/php-ir

A focused PHP library implementing classical **Information Retrieval (IR)**
algorithms based on the Stanford IR book  
[Introduction to Information Retrieval](https://nlp.stanford.edu/IR-book/html/htmledition/irbook.html).

The goal of this project is to provide a **correct, deterministic, and
explainable IR core** suitable for search, clustering, and recommendation
systems.

---

## License
MIT

---

## Scope

- Vector space model (dense and sparse vectors)
- Similarity measures (cosine, euclidean)
- Term weighting:
    - Term Frequency (TF)
    - Inverse Document Frequency (IDF)
    - TF-IDF
    - BM25 (probabilistic ranking)
- Clustering (K-Means, K-Means++)
- Deterministic, explainable algorithms

---

## Non-Goals

- NLP pipelines
- Transformers / embeddings
- Semantic vector databases
- Dataset abstractions
- Framework integrations

This library intentionally focuses on **classical IR**, not modern NLP.

---

## Philosophy

This library favors:

- Mathematical correctness
- Explicit, inspectable abstractions
- Deterministic behavior
- Minimal hidden state
- Native acceleration via Zephir (optional, future-facing)

---

## API Stability

New functionality will be added in a backward-compatible manner.

---

## Releasing

Project versions are tracked using a `.version` file and Git tags.

To bump the version:

```bash
./scripts/bump-version.sh {major|minor|patch}
git commit -am "chore(release): bump version"